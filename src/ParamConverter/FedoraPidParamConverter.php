<?php

namespace Drupal\pid_redirect\ParamConverter;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;

/**
 * Load a node based on being given a Fedora PID.
 */
class FedoraPidParamConverter implements ParamConverterInterface {

  /**
   * The node storage service.
   *
   * @var \Drupal\Core\Entity\ContentEntityStorageInterface|\Drupal\Core\Entity\EntityStorageInterface
   */
  protected ContentEntityStorageInterface $nodeStorage;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->nodeStorage = $entity_type_manager->getStorage('node');
  }

  /**
   * {@inheritDoc}
   */
  public function convert($value, $definition, $name, array $defaults) {
    $loaded = $this->nodeStorage->loadByProperties(['field_pid' => $value]);
    if (!empty($loaded)) {
      return reset($loaded);
    }
  }

  /**
   * {@inheritDoc}
   */
  public function applies($definition, $name, Route $route) {
    return !empty($definition['type']) && $definition['type'] == 'fedora_pid';
  }

}
