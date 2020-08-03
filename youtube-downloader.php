<?php
  /*
   * ### TODO ###
   * - Add function to download files of different video/audio quality
   */

  //Libraries
  require("./vendor/autoload.php");

  //Classes
  use \YouTube\YouTubeDownloader;
  use \YouTube\Browser;

  //Objects
  $youtube = new YouTubeDownloader();
  $browser = new Browser();

  //Variables
  $ytVideos = array();
  $ytLinks = [""];
  $ytFormat = "mp4, video, 720p, audio";

  //Prepare video array for download
  foreach ($ytLinks as $ytLink)
  {
    $ytVideos[]["yt_link"] = $ytLink;
  }

  //Download video routine
  foreach($ytVideos as $idx => $ytVideo)
  {
    print("Looking for " . $ytVideo["yt_link"] . " info ...\n");
    $ytVideo["info"] = $youtube->getDownloadLinks($ytVideo["yt_link"]);

    if ($ytVideo["info"])
    {
      print($ytVideo["yt_link"] . " info acquired:\n");
      print_r($ytVideo["info"]);

      print("Looking for \"" . $ytFormat . "\" file link to download ...\n");
      foreach($ytVideo["info"] as $ytInfo)
      {
        if ($ytInfo["format"] == $ytFormat)
        {
          $ytVideo["dl_link"] = $ytInfo["url"];
          print($ytVideo["yt_link"] . " \"" . $ytFormat . "\" file link acquired:\n");
          print($ytVideo["dl_link"] . "\n");
          
          //Download the video
          print($ytVideo["yt_link"] . " \"" . $ytFormat . "\" file downloading ...\n");
          $fileName = __DIR__ . "/videos/" . ($idx + 1) . ".mp4";
          $ytVideo["downloaded"] = $browser->downloadToFile($ytVideo["dl_link"], $fileName);
          if ($ytVideo["downloaded"])
            print($ytVideo["yt_link"] . " \"" . $ytFormat . "\" file downloaded!\n");
          else
            print($ytVideo["yt_link"] . " \"" . $ytFormat . "\" file download error!\n");
        }
      }
    }
    else
    {
      print($ytVideo["yt_link"] . " info error:\n");
      print_r($youtube->getLastError());
    }
  }
?>