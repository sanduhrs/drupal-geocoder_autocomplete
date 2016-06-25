<?php
/**
 * @file
 * Contains /Drupal/geocoder_autocomplete/GeocoderJsonConsumer.
 */

namespace Drupal\geocoder_autocomplete;

use Drupal\Core\Url;
use GuzzleHttp\ClientInterface;

/**
 * Defines the GeocoderJsonConsumer service, for return parse GeoJson.
 */
class GeocoderJsonConsumer {
  /**
   * Drupal http client.
   *
   * @var GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Service contstructor.
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * Return json list of geolocation matching $text.
   *
   * @param string $text
   *   The text query for search a place.
   *
   * @return array
   *   An array of matching location.
   */
  public function getAddress($text) {
    $language_interface = \Drupal::languageManager()->getCurrentLanguage();
    $language = isset($language_interface) ? $language_interface->getId() : 'en';

    $query = [
      'address' => $text,
      'language' => $language,
      'sensor' => 'false',
    ];
    $uri = 'http://maps.googleapis.com/maps/api/geocode/json';
    $url  = Url::fromUri($uri, array('query' => $query));

    $response = $this->httpClient->request('GET', $uri, [
      'query' => $query,
    ]);

    $matches = array();
    if (empty($response->error)) {
      $data = json_decode($response->getBody());
      if ($data->status == 'OK') {
        foreach ($data->results as $result) {
          if (!empty($result->formatted_address)) {
            $formatted_address = $result->formatted_address;
            // Names containing commas or quotes must be wrapped in quotes.
            $matches[] = array('value' => $formatted_address, 'label' => $formatted_address);
          }
        }

      }
    }

    return $matches;
  }

}
