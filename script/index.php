<?php
require 'vendor/autoload.php';
use Goutte\Client;
$client = new Client();
$crawler = $client->request('GET', 'http://1000mostcommonwords.com/');
$crawler->filter('.entry-content p span a')->each(function ($node) {
    $filename = trim(basename($node->attr('href')));
    $client = new Client();
    $crawler_lang = $client->request('GET', $node->attr('href'));
    $crawler_lang->filter('table td:nth-child(2)')->each(function ($node_lang) use ($filename) {
        file_put_contents($filename . '.txt', $node_lang->text() . PHP_EOL, FILE_APPEND);
    });
});
