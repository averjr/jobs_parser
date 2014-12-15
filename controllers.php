<?php

function uber_trim($s)
{
    $s = preg_replace('/\xA0/u', ' ', $s);  // strips UTF-8 NBSP: "\xC2\xA0"
    $s = trim($s);
    return $s;
}


function grabjob_action()
{
    $dom = new DomDocument;
    $dom->loadHtmlFile('http://trud.gov.ua/control/ru/job/vacancy_info?vacancy_id=388306758233842');

    $xpath = new DomXPath($dom);

    $data = array();

    foreach ($xpath->query("//tr[preceding-sibling::tr[@class='border_head']][td[@class='defaultb']]") as $node) {
        foreach ($xpath->query('preceding-sibling::tr[@class="border_head"][1]', $node) as $cell) {
            $headerName = $cell->nodeValue;
        }

        $rowData = array();

        foreach ($xpath->query('td', $node) as $cell) {
            $rowData[] = uber_trim($cell->nodeValue);
        }

        $data[$headerName][] = $rowData;
    }

    $Job = new Job();
    $job = $Job->job_save($data);
}

function show_action()
{
    $Job = new Job();
    $job = $Job->get_job();
    require 'templates/show.php';
}

function edit_action()
{
    $Job = new Job();
    $job = $Job->get_job();
    require 'templates/edit.php';
}

function update_action()
{
    $Job = new Job();
    $job = $Job->save_job();
}

function delate_action()
{


}