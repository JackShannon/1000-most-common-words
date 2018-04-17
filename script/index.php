<?php
require 'vendor/autoload.php';
use Goutte\Client;

shell_exec('rm ' . dirname(__DIR__) . DIRECTORY_SEPARATOR . '*.txt');

$client = new Client();
$crawler = $client->request('GET', 'http://1000mostcommonwords.com/');
$crawler->filter('.entry-content p span a')->each(function ($node) {
    $filename = trim(basename($node->attr('href')));
    $client = new Client();
    $crawler_lang = $client->request('GET', $node->attr('href'));
    $column = (preg_match('/english/', $node->attr('href')) ? 3 : 2);
    $crawler_lang->filter('table tr:not(:first-child) td:nth-child('.$column.')')->each(function ($node_lang) use ($filename) {
        $filename = dirname(__DIR__) . DIRECTORY_SEPARATOR . $filename . '.txt';
        $word = trim($node_lang->text(), ',.') . PHP_EOL;
        file_put_contents($filename, $word, FILE_APPEND);
    });
});
