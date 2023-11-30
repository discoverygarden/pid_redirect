<?php

namespace Drupal\pid_redirect\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\CacheableRedirectResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect to nodes based on the given PID.
 */
class IslandoraRedirectController extends ControllerBase {

  /**
   * Controller callback.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The loaded node to which to redirect.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response.
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   *   Propagated from the ::toUrl() call.
   */
  public function content(NodeInterface $node) : Response {
    $cache_meta = CacheableMetadata::createFromObject($node)
      ->addCacheContexts(['url']);

    $url = $node->toUrl()->toString(TRUE);
    return (new CacheableRedirectResponse(
      $url->getGeneratedUrl(),
      Response::HTTP_PERMANENTLY_REDIRECT,
    ))
      ->addCacheableDependency($cache_meta)
      ->addCacheableDependency($url);
  }

}
