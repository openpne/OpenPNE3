<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opOpenSocialGenkeyTask
 *
 * @author ShogoKawahara <kawahara@tejimaya.net>
 */
class opOpenSocialGenkeyTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace = 'opOpenSocial';
    $this->name      = 'generate-key';

    $this->addOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application', true);
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');

    $this->briefDescription = 'Generate Key for RSA-SHA1';
    $this->detailedDescription = <<<EOF
The [opOpenSocial:genkey|INFO] task generate RSA private key and x509 certificate.
Call it with:

 [./symfony opOpenSocial:genkey|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if (! extension_loaded('openssl'))
    {
      throw Exception('this task requires the openssl php extension, see http://www.php.net/openssl');
    }

    $days = null;
    while (
      !is_numeric($days)
    )
    {
      $days = $this->ask('The Days of Validity (default:365)');
      if (!$days)
      {
        $days = 365;
      }
    }

    while (
      !($phrase = $this->ask('Private Key Phrase'))
    );

    $country = null;
    while (
      !($country = strtoupper($this->ask('Country Name (2 letter code)')))
      || strlen($country) != 2
    )
    {
      $this->logBlock('invalid format.', 'ERROR');
    }

    while (
      !($state = $this->ask('State or Province Name (full name)'))
    );

    while (
      !($locality = $this->ask('Locality Name (eg,city)'))
    );

    while (
      !($org = $this->ask('Organization Name(eg,company)'))
    );

    while (
      !($orgUnit = $this->ask('Organization Unit Name(eg,section)'))
    );

    while (
      !($common = $this->ask('Common Name(eg,Your name)'))
    );

    while (
      !($email = $this->ask('Email Address'))
    );

    $dn = array(
      'countryName' => $country,
      'stateOrProvinceName' => $state,
      'localityName' => $locality,
      'organizationName' => $org,
      'organizationalUnitName' => $orgUnit,
      'commonName' => $common,
      'emailAddress' => $email
    );

    $dirname = sfConfig::get('sf_plugins_dir').'/opOpenSocialPlugin/certs';

    $filesystem = new sfFilesystem($this->dispatcher, $this->formatter);
    $filesystem->mkdirs($dirname);

    $privatekey = openssl_pkey_new();

    $csr = openssl_csr_new($dn, $privatekey);
    $sscert = openssl_csr_sign($csr, null , $privatekey, $days);

    openssl_x509_export($sscert, $certout);
    openssl_pkey_export($privatekey, $pkeyout, $phrase);

    $cert_filename = $dirname.'/public.crt';
    file_put_contents($cert_filename , $certout);
    $this->logSection('file+', $cert_filename);

    $pkey_filename = $dirname.'/private.key';
    file_put_contents($pkey_filename ,$pkeyout);
    $this->logSection('file+', $pkey_filename);

    $databaseManager = new sfDatabaseManager($this->configuration);
    Doctrine::getTable('SnsConfig')->set('shindig_private_key_phrase', $phrase);
  }
}
