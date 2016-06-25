<?php
/**
 * @file
 * Contains \Drupal\geocoder_autocomplete\Controller\DefaultController.
 */

namespace Drupal\geocoder_autocomplete\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\geocoder_autocomplete\GeocoderJsonConsumer;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Default controller for the geocoder_autocomplete module.
 */
class GeocoderController extends ControllerBase {
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('geocoderautocomplete.consumer')
    );
  }
  /**
   * Geocoder service.
   *
   * @var \Drupal\geocoder_autocomplete\GeocoderJsonConsumer
   */
  protected $geocoderService;
  /**
   * Constructs a GeocoderController object.
   *
   * @param \Drupal\geocoder_autocomplete\GeocoderJsonConsumer $geocoder
   *   A geocoder service.
   */
  public function __construct(GeocoderJsonConsumer $geocoder) {
    $this->geocoderService = $geocoder;
  }
  /**
   * Callback Method for Route geocoder_autocomplete.autocomplete.
   *
   * @param Request $request
   *   The Request sent.
   *
   * @return mixed|string
   *   Json output of the found strings.
   */
  public function geocoderAutocomplete(Request $request) {
    $matches = $this->geocoderService->getAddress(
      $request->query->get('q')
    );

    return new JsonResponse($matches);
  }

}
