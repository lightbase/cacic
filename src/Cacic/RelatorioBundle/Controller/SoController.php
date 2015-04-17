<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 16/04/15
 * Time: 17:22
 */

namespace Cacic\RelatorioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cacic\WSBundle\Helper\TagValueHelper;
use Cacic\CommonBundle\Form\Type\SoPesquisaType;
use Ddeboer\DataImport\Reader\ArrayReader;
use Ddeboer\DataImport\Writer\CsvWriter;
use Ddeboer\DataImport\ValueConverter\CallbackValueConverter;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Ddeboer\DataImport\ValueConverter\CharsetValueConverter;
use Ddeboer\DataImport\Workflow;


class SoController extends Controller {

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();

        $form = $this->createForm( new SoPesquisaType() );

        return $this->render( 'CacicRelatorioBundle:So:index.html.twig',
            array(
                'locale'=> $locale,
                'form' => $form->createView()
            )
        );
    }

    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();
        $logger = $this->get('logger');

        $idSo = $request->get('idSo');
        if (empty($idSo)) {
            // Aqui vem via POST pelo formulário
            $data = $request->request->all();
            if (!empty($data)) {
                $idSo = $data['log_pesquisa']['idLocal'];
            } else {
                $idSo = null;
            }
        }

        $computadores = $em->getRepository("CacicCommonBundle:So")->listarSo($idSo);

        $TotalnumComp = 0;

        foreach ($computadores as $cont  ){
            $TotalnumComp += $cont['numComp'];
        }

        return $this->render( 'CacicRelatorioBundle:So:listar.html.twig',
            array(
                'idioma'=> $locale,
                'filtroLocais' => $idSo,
                'logs' => ( isset( $computadores ) ? $computadores : null ),
                'totalnumcomp' => $TotalnumComp
            )
        );

    }

    public function listarCsvAction(Request $request, $idSo) {

        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();

        $idSo = $request->get('idSo');
        if (empty($idSo)) {
            // Aqui vem via POST pelo formulário
            $data = $request->request->all();
            if (!empty($data)) {
                $idSo = $data['idLocal'];
            } else {
                $idSo = null;
            }
        }

        $computadores = $em->getRepository("CacicCommonBundle:So")->listarSoCsv($idSo);

        $TotalnumComp = 0;

        foreach ($computadores as $cont  ){
            $TotalnumComp += $cont['numComp'];
        }

        // Gera CSV
        $reader = new ArrayReader($computadores);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'Computadores-Subredes');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $writer->writeItem(array('Sistema Operacional', 'Local', 'Subrede', 'IP da Subrede', 'Total de Estações'));
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="SO-Subredes.csv"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;

    }

    public function detalharAction(Request $request, $idSo) {
        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();

        $idRede = $request->get('idRede');
        $idLocal = $request->get('idLocal');

        $so = $em->getRepository("CacicCommonBundle:So")->detalhar(array($idSo), $idRede, $idLocal);

        return $this->render('CacicRelatorioBundle:So:detalhar.html.twig',
            array(
                'idioma' => $locale,
                'so' => $so,
                'idSo' => $idSo,
                'idRede' => $idRede,
                'idLocal' => $idLocal
            )
        );
    }

    public function detalharCsvAction(Request $request, $idSo) {
        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();

        $idRede = $request->get('idRede');
        $idLocal = $request->get('idLocal');

        $so = $em->getRepository("CacicCommonBundle:So")->detalharCsv(array($idSo), $idRede, $idLocal);

        // Gera CSV
        $reader = new ArrayReader($so);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        $converter = new CallbackValueConverter(function ($input) {
            return $input->format('d/m/Y H:m:s');
        });
        $workflow->addValueConverter('dtHrUltAcesso', $converter);

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'Computadores-lista');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $writer->writeItem(array('Computador','Mac Address','Endereço IP','Sistema Operacional','Local','Subrede','IP Subrede','Data/Hora do Ùltimo Acesso'));
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="Computadores-SO.csv"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    public function consolidadoAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();

        $so_lista = $em->getRepository('CacicCommonBundle:Computador')->countPorSO();

        return $this->render('CacicRelatorioBundle:So:consolidado.html.twig',
            array(
                'idioma' => $locale,
                'so_lista' => $so_lista
            )
        );

    }

    public function consolidadoCsvAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $locale = $request->getLocale();

        $so_lista = $em->getRepository('CacicCommonBundle:Computador')->countPorSOCsv();

        // Gera CSV
        $reader = new ArrayReader($so_lista);

        // Create the workflow from the reader
        $workflow = new Workflow($reader);

        $workflow->addValueConverter("reader",new CharsetValueConverter('UTF-8',$reader));

        $converter = new CallbackValueConverter(function ($input) {
            return $input->format('d/m/Y H:m:s');
        });
        $workflow->addValueConverter('dtHrUltAcesso', $converter);

        // Add the writer to the workflow
        $tmpfile = tempnam(sys_get_temp_dir(), 'Computadores-lista');
        $file = new \SplFileObject($tmpfile, 'w');
        $writer = new CsvWriter($file);
        $writer->writeItem(array('Sistema Operacional','Total de Estações'));
        $workflow->addWriter($writer);

        // Process the workflow
        $workflow->process();

        // Retorna o arquivo
        $response = new BinaryFileResponse($tmpfile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="So-Consolidado.csv"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;

    }

}