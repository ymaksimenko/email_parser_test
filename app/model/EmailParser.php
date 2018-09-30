<?php
namespace App\Model;

use App\Model\Parser;

class EmailParser extends Parser
{
    public function Parse($urls)
    {
        $result = [];

            foreach ($urls as $item) {
                $data = $this->GetPage($item);
                $matches = [];
                preg_match_all("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$data,$matches);
                if (!empty($matches[0])) {
                    $result = array_merge($result, $matches[0]);
                }
            }

        return array_unique($result);
    }
}