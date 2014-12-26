<?php
namespace Cacic\CommonBundle\Controller;

use Ddeboer\DataImport\ValueConverter\CharsetValueConverter;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Reader\ArrayReader;
use Ddeboer\DataImport\Writer\CsvWriter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Ddeboer\DataImport\ValueConverter\CallbackValueConverter;
use Ddeboer\DataImport\ItemConverter\CallbackItemConverter;


/*
 * Gerar arquivos csv para carga do SGConf_PGFN
 */

class SGConfController extends Controller {

    /*
     * Página com botões csv para carga no SGConf_PGFN
     */
    public function arquivoSgconfAction (){

        return $this->render( 'CacicCommonBundle:Sgconf:index.html.twig',
            array()
        );
    }

    /*
     * Arquivo csv de Locais
     */
    public function arquivoLocalAction (Request $request){

        $printers = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Local' )->localSGConf();

        // Gera CSV
        $reader = new ArrayReader($printers);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'SGConf_PGFN_Locais_CACIC_');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        //$writer->writeItem(array('ID Local', 'Nome Local','SG Local'));
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Gera data para adicionar no nome do csv
        $today = date("Ymd");
        $nameArquivo = "SGConf_PGFN_Locais_CACIC_".$today.".csv";

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename= '.$nameArquivo.'');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /*
    * Arquivo csv de Redes
    */
    public function arquivoRedeAction (Request $request){

        $printers = $this->getDoctrine()->getRepository( 'CacicCommonBundle:Rede' )->redeSGConf();

        // Gera CSV
        $reader = new ArrayReader($printers);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'SGConf_PGFN_Redes_CACIC_');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        //$writer->writeItem(array('IP Rede','ID Local','Nome Rede'));
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Gera data e adiciona no nome do arquivo
        $today = date("Ymd");
        $nameArquivo = "SGConf_PGFN_Redes_CACIC_".$today.".csv";

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$nameArquivo.'');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /*
    * Arquivo csv de Sistemas Operacionais
    */
    public function arquivoSoAction (Request $request){

        $printers = $this->getDoctrine()->getRepository( 'CacicCommonBundle:So' )->soSGConf();

        // Gera CSV
        $reader = new ArrayReader($printers);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));


        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'SGConf_PGFN_SO_CACIC_');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        //$writer->writeItem(array('ID SO','Descrição do SO'));
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Gera data e adiciona no nome do arquivo
        $today = date("Ymd");
        $nameArquivo = "SGConf_PGFN_SO_CACIC_".$today.".csv";

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$nameArquivo.'');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /*
    * Arquivo csv de Estações
    */
    public function arquivoEstacaoAction (Request $request){


        $printers = $this->getDoctrine()->getManager()->getRepository('CacicCommonBundle:Computador')->estacaoSGConf();
        // Gera CSV
        $reader = new ArrayReader($printers);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        // As you can see, the first names are not capitalized correctly. Let's fix
        // that with a value converter:
        $converter = new CallbackValueConverter(function ($input) {
            return $input->format('d/m/Y H:m:s');
        });
        $workflow->addValueConverter('dtHrUltAcesso', $converter);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'SGConf_PGFN_Estacoes_CACIC_');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $workflow->addWriter($writer);



        // Process the workflow
        $workflow->process();

        // Gera data e adiciona no nome do arquivo
        $today = date("Ymd");
        $nameArquivo = "SGConf_PGFN_Estacoes_CACIC_".$today.".csv";

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$nameArquivo.'');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

}