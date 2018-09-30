<?php
namespace App\Model;

use App\Model\Parser;

class UrlParser extends Parser
{
    public function Parse($site, $lavel = 0)
    {
        if ($site[count($site)-1] != '/') {
            $site .= '/';
        }

        if ($lavel == 0) {
            return [$site];
        }


        $urls = [
            0 => [$site]
        ];

        for ($i = 0; $i<=$lavel; $i++) {
            foreach ($urls[$i] as $item) {
                $data = $this->GetPage($item);

                if (!empty($data)) {
                    $data = str_replace("'", "\"", $data);
                    $matches = [];
                    preg_match_all('#<a [^>]*href="(.*)"[^>]*>#Ui', $data,$matches);
                    if (!empty($matches[1])) {
                        foreach ($matches[1] as &$link) {
                            $link = trim($link);
                            $url_ = '';
                            // Ссылка не на текущую страницу и это не почта
                            if (!empty($link) && $link[0]  != '#' && stripos($link, 'mailto:') === false) {
                                // Ссылка абсолютная в переделах сканируемого сайта
                                if (stripos($link, $site) !== false) {
                                    $url_ = $link;
                                } else {
                                    // Ссылка не ведет на внешний ресурс и она относительная
                                    if (preg_match("/^http|https/i", $link) === false) {
                                        $url_ = $this->absoluteLink($site, $link);
                                    }
                                }
                            }

                            $url_ = filter_var($url_, FILTER_VALIDATE_URL) ? $url_ : '';
                            if (!empty($url_)) {
                                $urls[$i+1][] = $url_;
                            }
                        }
                    }

                    $urls[$i+1] = array_unique($urls[$i+1]);
                }
            }
        }

        $result = [];

        foreach ($urls as $url_lavel) {
            $result = array_merge($result, $url_lavel);
        }

        return array_unique($result);
    }

    public function absoluteLink($site, $link)
    {
        $link_ = '';
        if(!preg_match("/^http:\/\//i", $link))
        {
            $link_ = ltrim($link ,"/");
            $link_ = $site . $link_;
        }

        return $link_;
    }
}