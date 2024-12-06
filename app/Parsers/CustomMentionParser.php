<?php
namespace App\Parsers;

use Illuminate\Support\Str;
use Xetaio\Mentions\Parser\MentionParser;

class CustomMentionParser extends MentionParser
{

    protected function replace(array $match): string
    {
        $character = $this->getOption('character');
        $mention = Str::title(str_replace($character, '', trim($match[0])));

        $route = config('mentions.pools.' . $this->getOption('pool') . '.route');

        $link = $route . $mention;
        $res = "<a class=\"link text-secondary\" href=\"{$link} \">{$character}{$mention}</a>";
        // Here we return a HTML link instead of the default Markdown.
        return $res;
    }

    public function parse($input)
    {
        if (is_null($input) || empty($input)) {
            return $input;
        }
        $regex = strtr($this->getOption('regex'), $this->getOption('regex_replacement'));

        preg_match_all($regex, $input, $matches);

        $matches = array_map([$this, 'mapper'], $matches[0]);

        $matches = $this->removeNullKeys($matches);
        $matches = $this->prepareArray($matches);
        $output = preg_replace_callback($matches, [$this, 'replace'], $input);
        return $output;
    }
}
