#!/usr/bin/php5 -q


<?php
foreach($argv as $value)
{
  $getCti = $value;
}

$v=explode(" ",$value);



require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\StreamingRecognitionConfig;
use Google\Cloud\Speech\V1\StreamingRecognizeRequest;
use Google\Cloud\Speech\V1\RecognitionConfig_AudioEncoding;

/**
 * Transcribe an audio file using Google Cloud Speech API
 * Example:
 * ```
 * $audoEncoding =  Google\Cloud\Speech\V1\RecognitionConfig_AudioEncoding::WAV
 * streaming_recognize('/path/to/audiofile.wav', 'en-US');
 * ```.
 *
 * @param string $audioFile path to an audio file.
 * @param string $languageCode The language of the content to
 *     be recognized. Accepts BCP-47 (e.g., `"en-US"`, `"es-ES"`).
 * @param string $encoding
 * @param string $sampleRateHertz
 *
 * @return string the text transcription
 */
function streaming_recognize($audioFile, $languageCode, $encoding, $sampleRateHertz)
{
    if (!defined('Grpc\STATUS_OK')) {
        throw new \Exception('Install the grpc extension ' .
            '(pecl install grpc)');
    }
   // if (!class_exists('Google\Cloud\Speech\V1\SpeechGrpcClient')) {
    //   throw new \Exception('Install the proto client PHP library ' .
     //       '(composer require google/proto-client)');
   // }
//    if (!class_exists('Google\GAX\GrpcConstants')) {
 //       throw new \Exception('Install the GAX library ' .
  //          '(composer require google/gax)');
   // }

    $speechClient = new SpeechClient();
    try {
        $config = new RecognitionConfig();
        $config->setLanguageCode($languageCode);
        $config->setSampleRateHertz($sampleRateHertz);
        // encoding must be an enum, convert from string
        $encodingEnum = constant(RecognitionConfig_AudioEncoding::class . '::' . $encoding);
        $config->setEncoding($encodingEnum);

        $strmConfig = new StreamingRecognitionConfig();
        $strmConfig->setConfig($config);

        $strmReq = new StreamingRecognizeRequest();
        $strmReq->setStreamingConfig($strmConfig);

        $strm = $speechClient->streamingRecognize();
        $strm->write($strmReq);

        $strmReq = new StreamingRecognizeRequest();
        $f = fopen($audioFile, "rb");
        $fsize = filesize($audioFile);
        $bytes = fread($f, $fsize);
        $strmReq->setAudioContent($bytes);
        $strm->write($strmReq);

        foreach ($strm->closeWriteAndReadAll() as $response) {
            foreach ($response->getResults() as $result) {
                foreach ($result->getAlternatives() as $alt) {
                    printf("Transcription: %s\n", $alt->getTranscript());
                }
            }
        }
    } finally {
        $speechClient->close();
    }
}
// function streaming_recognize($audioFile, $languageCode, $encoding, $sampleRateHertz)

$resp=streaming_recognize("/dev/shm/$v[0].flac","pt-BR","FLAC",8000);
print_r($resp);


?>
