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
        $em = $this->getDoctrine()->getManager();
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

        #$linuxDir = $cacicDir . "linux/";
        #if (!is_dir($linuxDir)) {
        #    mkdir($linuxDir);
        #}
        #
        #$windowsDir = $cacicDir . "windows/";
        #if (!is_dir($windowsDir)) {
        #    mkdir($windowsDir);
        #}


        $outrosDir = $downloadsDir . "outros/";
        if (!is_dir($outrosDir)) {
            mkdir($outrosDir);
        }
        // Carrega a lista de Agentes por plataforma
        $tipo_so = $em->getRepository('CacicCommonBundle:TipoSo')->findAll();


        $form = $this->createForm( new AgenteType(), null, array(
            'tipo_so' => $tipo_so
        ));
        $locale = $request->getLocale();

        //$logger->debug("4444444444444444444444444444444444 ".print_r($saida, true));

        if ( $request->isMethod('POST') )
        {
            // Aqui vamos fazer o tratamento dos agentes
            $data = $form->getData();
            $data['version'] = $request->get('agentes')['version'];
            $files = $request->files->get('agentes');

            //$logger->debug("99999999999999999999999999999999999 ".print_r($data, true));
            if (!empty($files)) {
                //$logger->debug("88888888888888888888888888888888888888 ".print_r($files['windows'], true));
                if (empty($data['version'])) {
                    $logger->error("O parâmetro versão é obrigatório");
                    $this->get('session')->getFlashBag()->add('error', 'O parâmetro versão é obrigatório');
                } else {
                    // Carrega as versões dos sistemas operacionais
                    $versionDir = $cacicDir . $data['version'];
                    $result = false;
                    foreach($tipo_so as $so) {
                        $tipoDir = $versionDir . "/" . $so->getTipo();
                        $result = $this->uploadPackage($files[$so->getTipo()], $tipoDir);
                    }

                    if (!$result) {
                        $logger->error("Erro na atualização dos Agentes");
                        $this->get('session')->getFlashBag()->add('error', 'Erro na atualização dos agentes');
                    } else {
                        // Make this version current
                        $logger->debug("Agentes atualizados com sucesso. Ajustando para versão $versionDir");
			            @unlink("$cacicDir"."current");
                        symlink($versionDir, "$cacicDir"."current");
                        $this->get('session')->getFlashBag()->add('success', 'Agentes atualizados com sucesso!');
                    }
                }
            }

        }

        // Constrói array de arquivos e hashes
        $finder = new Finder();
        $saida = array();
        $base_url = $request->getBaseUrl();
        $base_url = preg_replace('/\/app.*.php/', "", $base_url);

        // Varre diretório do Cacic
        $finder->depth('== 0');
        $finder->directories()->in($cacicDir);

        // Agora busca diretórios de versão
        $saida['tipo_so'] = array();
        foreach($finder as $version) {
            //$logger->debug("1111111111111111111111111111111 ".$version->getFileName());
            if ($version->getFileName() == 'current') {
                continue;
            }

            // Agora busca um diretório pra cada tipo de SO
            foreach($tipo_so as $so) {
                $saida['tipo_so'][$so->getTipo()][$version->getFileName()] = array();
                $agentes_path = $version->getRealPath() . "/" . $so->getTipo();

                // Cria diretório se não existir
                if (!is_dir($agentes_path)) {
                    mkdir($agentes_path);
                }
                $agentes = new Finder();
                $agentes->files()->in($agentes_path);
                foreach($agentes as $file) {
                    array_push($saida['tipo_so'][$so->getTipo()][$version->getFileName()], array(
                        'name' => $file->getFileName(),
                        'download_url' => $base_url . '/downloads/cacic/' . $version->getFileName() . '/' . $so->getTipo() . "/" . $file->getFileName(),
                        'hash' => md5_file($file->getRealPath()),
                        'size' => $file->getSize(),
                        'filename' => 'cacic/' . $version->getFileName() . '/' . $so->getTipo() . "/" . $file->getFileName()
                    ));
                }
            }
        }


        // Get latest version
        $current = @basename(@readlink($cacicDir."current"));
        $saida['live_version'] = $current;

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

        //$logger->debug("3333333333333333333333333333333333333333 ".print_r($saida, true));

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
