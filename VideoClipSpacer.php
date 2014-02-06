<?php


if (!(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')) {
    echo "Unfortunately, this script currently only runs on Windows.\n";
    //exit;
}

if (count($argv) < 3) {
    die(
"There are too few arguments.

    php ViewClipSpacer.php input.xml output.xml

");
}

exit;
date_default_timezone_set("UTC");

// Load the $xml file.
$xml = simplexml_load_file($argv[1]);
$sxe = new SimpleXMLElement($xml->asXML());

// Read the first video track and the stereo audio tracks.
$videos = $sxe->{"project"}->{"children"}->{"sequence"}->{"media"}->{"video"}->{"track"}[0]->{"clipitem"};
$audios1 = $sxe->{"project"}->{"children"}->{"sequence"}->{"media"}->{"audio"}->{"track"}[0]->{"clipitem"};
$audios2 = $sxe->{"project"}->{"children"}->{"sequence"}->{"media"}->{"audio"}->{"track"}[1]->{"clipitem"};

echo "Video files found:      ".count($videos)."\n";
echo "Audio (1) tracks found: ".count($audios1)."\n";
echo "Audio (2) tracks found: ".count($audios2)."\n";

// Parse through the video files.
for ($i = 0; $i < count($videos); $i++) { 
    echo "\tTitle: ".$videos[$i]->{"name"}."\n";
    $file = str_replace("/", "\\",
                str_replace("%20", " ",
                    str_replace("%3a/", ":\\",
                        str_replace("file://localhost/", "",
                            $videos[$i]->{"file"}->{"pathurl"}
                        )
                    )
                )
            );

    echo "\t\tStart time (Video):   ".$videos[$i]->{"start"}."\n";
    echo "\t\tStart time (Audio 1): ".$audios1[$i]->{"start"}."\n".
    echo "\t\tStart time (Audio 2): ".$audios2[$i]->{"start"}."\n";

    $create_time = filectime($file);
    if ($i == 0) {
        $base = $create_time;
    }
    echo "\t\tCreate time:          ".date("r", $create_time)."\t";
    $new_time = (($create_time-$base)*24);
    echo "\t\tNew Start Time:       ".$new_time."\t";
    $videos[$i]->{"start"} = $new_time;
    $videos[$i]->{"end"} =  $videos[$i]->{"start"} + $videos[$i]->{"duration"};
    $audios1[$i]->{"start"} = $new_time;
    $audios1[$i]->{"end"} =  $videos[$i]->{"start"} + $videos[$i]->{"duration"};
    $audios2[$i]->{"start"} = $new_time;
    $audios2[$i]->{"end"} =  $videos[$i]->{"start"} + $videos[$i]->{"duration"};
    $sxe->{"project"}->{"children"}->{"sequence"}->{"duration"} = $videos[$i]->{"start"} + $videos[$i]->{"duration"};
}

echo "Now writing the output xml file... ";
$sxe->asXML($argv[2]);
echo "done!\n\n";
