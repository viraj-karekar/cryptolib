<?php

namespace Drupal\encryptdecrypt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\encryptdecrypt\Client\EncryptDecryptFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class EncryptDecryptForm
 * @package Drupal\encryptdecrypt\Form
 */
class EncryptDecryptForm extends FormBase {

  /**
   * @var EncryptDecryptFactory
   */
  protected $encryptionFactoryManager;

  /**
   * @var MessengerInterface
   */
  protected  $messenger;

  /**
   * EncryptDecryptForm constructor.
   * @param EncryptDecryptFactory $encryption_factory
   * @param MessengerInterface $messenger
   */
  public function __construct(EncryptDecryptFactory $encryption_factory, MessengerInterface $messenger) {
    $this->encryptionFactoryManager = $encryption_factory;
    $this->messenger = $messenger;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container){
    return new static(
      $container->get('encryptdecrypt.factory'),
      $container->get('messenger')
    );
  }

  /**
   * @inheritDoc
   */
  public function getFormId()
  {
    // TODO: Implement getFormId() method.
    return "encrypt_decrypt_form";
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    // TODO: Implement buildForm() method.
    $form['encryption_library'] = [
      '#type' => 'select',
      '#title' => $this->t('Select encryption library'),
      '#options' => [
        'openssl' => $this->t('OpenSSL'),
        'mcrypt' => $this->t('MCrypt'),
      ],
      '#required' => true,
    ];

    $form['ed_string'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter text'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Encrypt/Decrypt'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    if (!extension_loaded($form_state->getValue('encryption_library'))) {
      $this->messenger->addError($this->t("Install %lib php extension.", [
        '%lib' => $form_state->getValue('encryption_library'),
      ]));
    }

    if (empty($form_state->getValue(ed_string))) {
      $form_state->setErrorByName('ed_string', $this->t("Enter valid string"));
    }
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // TODO: Implement submitForm() method.
    $encryptionLib = $this->encryptionFactoryManager->getClient($form_state->getValue('encryption_library'));
    $encrypted = $encryptionLib->encrypt($form_state->getValue('ed_string'));
    $decrypted = $encryptionLib->decrypt($encrypted);

    $this->messenger->addStatus($this->t("The encrypted value: %val", [
      '%val' => $encrypted,
    ]));
    $this->messenger->addStatus($this->t("The decrypted value: %val", [
      '%val' => $decrypted,
    ]));
  }

}
