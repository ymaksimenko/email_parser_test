<?php
namespace App\Model;

use App\Core\AbstractModel;

class Parser extends AbstractModel
{
    private $site;
    private $lavel;
    private $email_count;
    private $complete_link_list = [];
    private $complete_email_list = [];

    public function __construct($site = null, $lavel = 0, $email_count = 1)
    {
        if (!empty($site)) {
            $this->site = $site;
            $this->lavel = $lavel;
            $this->email_count = $email_count;
        }
    }

    protected function GetPage($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($response_code == 200) ? $response : null;
    }


    public function ParseDataFromPage($page_html_data, $data_type = 'email') {
        $result = [];
        $matches = [];
        $host = parse_url($this->site, PHP_URL_SCHEME) . "://" . parse_url($this->site, PHP_URL_HOST);;
        switch ($data_type) {
            case 'email':
                preg_match_all("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $page_html_data, $matches);
                $result = !empty($matches[0]) ? array_unique($matches[0]) : [];
                break;
            case 'link':
                $data = str_replace("'", "\"", $page_html_data);
                preg_match_all('#<a [^>]*href="(.*)"[^>]*>#Ui', $data, $matches);
                if (!empty($matches[1])) {
                    $matches = array_unique(array_map("trim", $matches[1]));
                    foreach ($matches as $link) {
                        $url_ = '';
                        // Ссылка не на текущую страницу и это не почта
                        if (!empty($link) && $link[0]  != '#' && stripos($link, 'mailto:') === false) {
                            // Ссылка абсолютная в переделах сканируемого сайта
                            if (stripos($link, $host) === 0) {
                                $url_ = $link;
                            } else {
                                // Ссылка не ведет на внешний ресурс и она относительная
                                if (preg_match("/^http|https/i", $link) === false) {
                                    $url_ = $this->absoluteLink($host, $link);
                                }
                            }
                        }

                        $url_ = filter_var($url_, FILTER_VALIDATE_URL) ? $url_ : '';
                        if (!empty($url_)) {
                            $result[] = $url_;
                        }
                    }
                }
                break;
        }
        return $result;
    }

    public function LinearParserStart($site, $lavel, $max_count) {
        if (!empty($site)) {
            $this->site = $site;
            $this->lavel = $lavel;
            $this->complete_link_list[] = $site;
        }
        $result = [];
        $email_left = $max_count;
        $current_lavel_urls = [['link'=>$site, 'parent_link'=>null]];

        for ($i = 0; $i <= $lavel; $i++) {
            $lavel_parse = $this->ParseLavel($current_lavel_urls, $email_left, $i);
            $result[$i] = $lavel_parse['result'];
            $current_lavel_urls = $lavel_parse['next_lavel'];
            $email_left -= $lavel_parse['email_count'];
            if ($email_left == 0) {
                break;
            }
        }

        // Save result to mongodb
        $site_collection = $this->getDB()->sites;
        $site_rec = $site_collection->insertOne(["site" => $site, "count_email"=>count($this->complete_email_list)]);
        $site_id = $site_rec->getInsertedId();
        $page_collection = $this->getDB()->site_pages;

        foreach ($result as $lavel_res) {
            foreach ($lavel_res as &$page) {
                $page['site'] = (string) $site_id;
                $page_collection->insertOne($page);
            }
        }

        return ['link_count' => count($this->complete_link_list), 'email_count'=>count(array_unique($this->complete_email_list))];
    }

    public function ParseLavel($lavel_urls, $mail_necessary, $current_lavel)
    {
        $next_link_lavel = [];
        $count_parse = 0;
        foreach ($lavel_urls as &$item) {
            $result_parse = $this->ParsePage($item['link']);

            if (!empty($result_parse)) {
                $email_unique = array_diff($result_parse['email'], $this->complete_email_list);
                if (!empty($email_unique)) {
                    $count_this = count($email_unique);
                    if (($count_parse + $count_this) > $mail_necessary) {
                        $email_unique = array_slice($email_unique, 0, $mail_necessary-$count_parse);
                        $count_parse = $mail_necessary;
                    }

                    $item['email'] = $email_unique;
                    $count_parse += $count_this;
                    $this->complete_email_list = array_merge($this->complete_email_list, $item['email']);
                }

                if ($count_parse == $mail_necessary) {
                    $next_link_lavel = [];
                    break;
                } else {
                    if (!empty($result_parse['link']) && $current_lavel != $this->lavel) {
                        $unique_url = array_diff($result_parse['link'], $this->complete_link_list);
                        $this->complete_link_list = array_merge($this->complete_link_list, $unique_url);
                        foreach ($unique_url as $next_link) {
                             $next_link_lavel[] = ['link'=>$next_link, 'parent_link'=>$item['link']];
                        }
                    }
                }
            }
        }

        $result = ['result'=>$lavel_urls, 'email_count' => $count_parse, 'next_lavel'=>$next_link_lavel];

        return $result;
    }

    public function ParsePage($page_url)
    {
        $result = [];

        $data = $this->GetPage($page_url);
        $email = $this->ParseDataFromPage($data);
        if (!empty($email)) {
            $result['email'] = $email;
        }

        $links = $this->ParseDataFromPage($data, 'link');
        if (!empty($links)) {
            $result['link'] = $links;
        }

        return $result;
    }

    public function absoluteLink($site, $link)
    {
        $link_ = '';
        if(!preg_match("/^http:\/\//i", $link))
        {
            $link_ = ltrim($link ,"/");
            $link_ = $site . "/" . $link_;
        }
        return $link_;
    }
}