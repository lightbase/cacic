<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 18/10/14
 * Time: 23:49
 */

namespace Cacic\CommonBundle\Controller;

use Cacic\CommonBundle\Form\Type\AgenteType;
use Cacic\CommonBundle\Form\Type\DeployType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use PharData;
use Symfony\Component\Security\Acl\Exception\Exception;
use ZipArchive;


class AgenteController extends Controller {

    public function indexAction(Request $request) {
        $logger = $this->get('logger');
        // Cria diretório dos agentes se não existir
        $rootDir = $this->container->get('kernel')->getRootDir();
        $webDir = $rootDir . "/../web/";
        $downloadsDir = $webDir . "downloads/";
        if (!is_dir($downloadsDir)) {
            mkdir($downloadsDir);
        }

        $cacicDir = $downloadsDir . "cacic/";
        if (!is_dir($cacicDir)) {
            mkdir($cacicDir);
        }

        $linuxDir = $cacicDir . "linux/";
        if (!is_dir($linuxDir)) {
            mkdir($linuxDir);
        }

        $windowsDir = $cacicDir . "windows/";
        if (!is_dir($windowsDir)) {
            mkdir($windowsDir);
        }


        $outrosDir = $downloadsDir . "outros/";
        if (!is_dir($outrosDir)) {
            mkdir($outrosDir);
        }


        $form = $this->createForm( new AgenteType() );
        $locale = $request->getLocale();

        // Constrói array de arquivos e hashes
        $finder = new Finder();
        $saida = array();
        $base_url = $request->getBaseUrl();
        $base_url = preg_replace('/\/app.*.php/', "/", $base_url);

        // Primeiro tratamos agentes Linux
        // A regra é que o agente mais atual estará na pasta current
        $finder->directories()->in($linuxDir);
        $saida['linux']['versions'] = array();
        foreach($finder as $version) {
            $agentes = new Finder();
            if ($version->getFileName() == 'current') {
                continue;
            }
            $saida['linux']['versions'][$version->getFileName()] = array();
            $agentes->files()->in($version->getRealPath());
            foreach ($agentes as $file) {
                array_push($saida['linux']['versions'][$version->getFileName()], array(
                    'name' => $file->getFileName(),
                    'download_url' => $base_url . 'downloads/cacic/linux/' . $version->getFileName() . '/' . $file->getFileName(),
                    'hash' => md5_file($file->getRealPath()),
                    'size' => $file->getSize(),
                    'filename' => 'cacic/linux/' . $version->getFileName() . '/' . $file->getFileName()
                ));

            }
        }
        // Get latest version
        $current = @basename(@readlink($linuxDir."current"));
        $saida['linux']['live_version'] = $current;

        // Aí tratamos Windows
        $finder->directories()->in($windowsDir);
        $saida['windows']['versions'] = array();
        foreach($finder as $version) {
            $agentes = new Finder();
            if ($version->getFileName() == 'current') {
                continue;
            }
            $saida['windows']['versions'][$version->getFileName()] = array();
            $agentes->files()->in($version->getRealPath());
	        //$logger->debug("1111111111111111111111111111111111111111111 ".$version->getRealPath());
            foreach ($agentes as $file) {
	    	    //$logger->debug("77777777777777777777777777777777777777777 $file");
                array_push($saida['windows']['versions'][$version->getFileName()], array(
                    'name' => $file->getFileName(),
                    'download_url' => $base_url . 'downloads/cacic/windows/' . $version->getFileName() . '/' . $file->getFileName(),
                    'hash' => md5_file($file->getRealPath()),
                    'size' => $file->getSize(),
                    'filename' => 'cacic/windows/' . $version->getFileName() . '/' . $file->getFileName()
                ));

            }
        }
        // Get latest version
        $current = @basename(@readlink($windowsDir."current"));
        $saida['windows']['live_version'] = $current;

        //$logger->debug("4444444444444444444444444444444444 ".print_r($saida, true));

        if ( $request->isMethod('POST') )
        {
            // Aqui vamos fazer o tratamento dos agentes
            $data = $form->getData();
            $data['windows_version'] = $request->get('agentes')['windows_version'];
            $data['linux_version'] = $request->get('agentes')['linux_version'];
            $files = $request->files->get('agentes');

            //$logger->debug("99999999999999999999999999999999999 ".print_r($data, true));
            if (!empty($files['windows'])) {
                //$logger->debug("88888888888888888888888888888888888888 ".print_r($files['windows'], true));
                if (empty($data['windows_version'])) {
                    $logger->error("O parâmetro versão é obrigatório");
                    $this->get('session')->getFlashBag()->add('error', 'O parâmetro versão é obrigatório');
                } else {
                    $versionDir = $windowsDir . $data['windows_version'];
                    $result = $this->uploadPackage($files['windows'], $versionDir);
                    if (!$result) {
                        $logger->error("Erro na atualização dos Agentes Windows");
                        $this->get('session')->getFlashBag()->add('error', 'Erro na atualização dos agentes Windows');
                    } else {
                        // Make this version current
                        $logger->debug("Agentes atualizados com sucesso. Ajustando para versão $versionDir");
			@unlink("$windowsDir"."current");
                        symlink($versionDir, "$windowsDir"."current");
                        $this->get('session')->getFlashBag()->add('success', 'Agentes atualizados com sucesso!');
                    }
                }
            }

            if (!empty($files['linux'])) {
                if (empty($data['linux_version'])) {
                    $logger->error("O parâmetro versão é obrigatório");
                    $this->get('session')->getFlashBag()->add('error', 'O parâmetro versão é obrigatório');
                } else {
                    $versionDir = $linuxDir . $data['linux_version'];
                    $result = $this->uploadPackage($files['linux'], $versionDir);
                    if (!$result) {
                        $logger->error("Erro na atualização dos Agentes Linux");
                        $this->get('session')->getFlashBag()->add('error', 'Erro na atualização dos agentes Linux');
                    } else {
                        // Make this version current
                        $logger->debug("Agentes atualizados com sucesso. Ajustando para versão $versionDir");
			@unlink("$linuxDir"."current");
                        symlink($versionDir, $linuxDir."current");
                        $this->get('session')->getFlashBag()->add('success', 'Agentes atualizados com sucesso!');
                    }
                }
            }

        }

        return $this->render( 'CacicCommonBundle:Agente:index.html.twig',
            array(
                'local'=>$locale,
                'saida' => $saida,
                'form' => $form->createView()
            )
        );
    }

    public function uploadPackage($file, $version) {
        $logger = $this->get('logger');
        if (!$file->isValid()) {
            $logger->error("Erro no upload do arquivo. Arquivo inválido\n".$file->getErrorMessage());
            $this->get('session')->getFlashBag()->add('error', "Erro no upload do arquivo. Arquivo inválido\n".$file->getErrorMessage());
            return false;
        }
        $result = false;
        mkdir($version);
        //$logger->debug("66666666666666666666666666666666666 ".print_r($file, true));

        $extension = $file->getClientOriginalExtension();
        //$logger->debug("00000000000000000000000000000000000000000 $extension | $version");

        if ($extension == 'zip') {
            $zip = new ZipArchive;
            if ($zip->open($file) === TRUE) {
                $zip->extractTo($version);
                $zip->close();
                $logger->debug("Arquivo .zip descompactado com sucesso ". $file->getClientOriginalName());
                $result = true;
            } else {
                $logger->error("Erro na descompactação do arquivo .zip ". $file->getClientOriginalName());
                $this->get('session')->getFlashBag()->add('error', "Erro na descompatcação do arquivo .zip\n".$file->getErrorMessage());
                $result = false;
            }

        } elseif ($extension == 'tar.gz') {
            try {
                // decompress from gz
                $tar = $version.$file->getClientOriginalName();
                $p = new PharData($tar, 0, $file);
                $p->decompress();

                // Now unarchive from tar
                $phar = new PharData($tar);
                $phar->extractTo($version);

                // Remove file
                unlink($tar);

                $result = true;
            } catch (Exception $e) {
                $logger->error("Erro na extração do arquivo .gz \n".$e->getMessage());
                $this->get('session')->getFlashBag()->add('error', "Erro na extração do arquivo .gz\n".$file->getErrorMessage());
                $result = false;
            }

        } else {
            $logger->error("Extensão inválida para upload dos agentes ".$extension);
            $this->get('session')->getFlashBag()->add('error', "Extensão inválida para upload dos agentes ".$extension);
            $result = false;
        }

        return $result;
    }

    public function excluirAction(Request $request) {
        if ( ! $request->isXmlHttpRequest() )
            throw $this->createNotFoundException( 'Página não encontrada' );



        $rootDir = $this->container->get('kernel')->getRootDir();
        $webDir = $rootDir . "/../web/";
        $downloadsDir = $webDir . "downloads/";
        $filepath = $downloadsDir . $request->get('id');

        $this->get('logger')->debug("Excluindo arquivo de agente ".$filepath);

        $result = unlink($filepath);

        if ($result) {
            $response = new Response( json_encode( array('status' => 'ok') ) );
            $response->headers->set('Content-Type', 'application/json');
        } else {
            $response = new Response( json_encode( array('status' => 'error') ) );
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }

    public function deployAction(Request $request) {
        $logger = $this->get('logger');
        // Cria diretório dos agentes se não existir
        $rootDir = $this->container->get('kernel')->getRootDir();
        $webDir = $rootDir . "/../web/";
        $downloadsDir = $webDir . "downloads/";
        if (!is_dir($downloadsDir)) {
            mkdir($downloadsDir);
        }

        $outrosDir = $downloadsDir . "outros/";
        if (!is_dir($outrosDir)) {
            mkdir($outrosDir);
        }


        $form = $this->createForm( new DeployType() );
        $locale = $request->getLocale();

        // Constrói array de arquivos e hashes
        $finder = new Finder();
        $agentes = new Finder();
        $saida = array();
        $base_url = $request->getBaseUrl();
        $base_url = preg_replace('/\/app.*.php/', "/", $base_url);

        // Tratamos upload de módulos genéricos
        $finder->files()->in($outrosDir);
        $saida['outros'] = array();
        foreach($finder as $file) {
            array_push($saida['outros'], array(
                'name' => $file->getFileName(),
                'download_url' => $base_url . 'downloads/outros/' . $file->getFileName(),
                'hash' => md5_file($file->getRealPath()),
                'size' => $file->getSize(),
                'filename' => "outros/" . $file->getFileName()
            ));

        }

        if ( $request->isMethod('POST') )
        {
            // Aqui vamos fazer o tratamento dos agentes
            $files = $request->files->get('deploy');

            //$logger->debug("99999999999999999999999999999999999 ".print_r($files, true));
            if (!empty($files['outros'])) {
                //$logger->debug("88888888888888888888888888888888888888 ".print_r($files['outros'], true));
                $result = $this->uploadFile($files['outros'], $outrosDir);
                if (!$result) {
                    $logger->error("Erro no upload do módulo");
                    $this->get('session')->getFlashBag()->add('error', 'Erro no upload do módulo');
                } else {
                    // Make this version current
                    $logger->debug("Upload do módulo realizado com sucesso");
                    $this->get('session')->getFlashBag()->add('success', 'Upload do módulo realizado com sucesso!');
                }
            }

        }

        $logger->debug("3333333333333333333333333333333333333333 ".print_r($saida, true));

        return $this->render( 'CacicCommonBundle:Agente:deploy.html.twig',
            array(
                'local'=>$locale,
                'saida' => $saida,
                'form' => $form->createView()
            )
        );
    }

    public function uploadFile($file, $version) {
        $logger = $this->get('logger');
        if (!$file->isValid()) {
            $logger->error("Erro no upload do arquivo. Arquivo inválido\n".$file->getErrorMessage());
            $this->get('session')->getFlashBag()->add('error', "Erro no upload do arquivo. Arquivo inválido\n".$file->getErrorMessage());
            return false;
        }

        mkdir($version);
        $file->move($version, $file->getClientOriginalName());
        $result = true;
        $logger->debug("Upload do módulo realizado com sucesso");

        return $result;
    }

} 
