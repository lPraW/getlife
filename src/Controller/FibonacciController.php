<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class FibonacciController extends AbstractController {

    /**
     * @Route("/fibonacci", name="fibonacci")
     */
    /**
     * Renderiza la vista donde se realizará la introducción de las fechas para su posterior procesamiento.
     *
     * @return Response - Con la vista.
     */
    public function show(): Response {
        return $this->render('fibonacci/fibonacci.html.twig');
    }

    /**
     * @Route("/fibonacci/process", name="process_fibonacci"), methods={"POST"})
     */
    /**
     * Función que procesa las fechas recibidas en la request y realiza la sucesión de Fibonacci
     *
     * @return Response - Con el resultado en caso de que los parámetros se reciban correctamente.
     */
    public function process(): Response {
        // Recibimos las fechas en la request.
        $startDate = $_REQUEST['startDate'];
        $endDate = $_REQUEST['endDate'];
        // Comprobamos que existan
        if ($startDate && $endDate) {
            // Recogemos sus timestamps
            $timestampStartDate = strToTime($startDate);
            $timestampEndDate = strToTime($endDate);
            // En caso de que el inicio sea mayor que el fin retornamos error.
            if ($timestampStartDate > $timestampEndDate) {
                return new Response('El inicio no puede ser mayor que el fin', Response::HTTP_OK);
            }

            // Declaramos un array de sucesiones de fibonacci que estarán comprendidas entre los timestamps.
            $fibonacciBetweenTimestamps = [];

            $i = 0;
            $loop = true;

            // Realizamos un bucle infinito hasta que las sucesiones de fibonacci sean mayores que el timestamp final.
            while ($loop) {
                $fibonacci = $this->series($i);
                if ($fibonacci >= $timestampStartDate && $fibonacci <= $timestampEndDate) {
                    $fibonacciBetweenTimestamps[] = $fibonacci;
                } else if ($fibonacci > $timestampEndDate) {
                    $loop = false;
                }
                $i++;
            }

            echo "<h2>Las sucesiones de Fibonacci comprendidas entre $timestampStartDate y $timestampEndDate son:</h2>";
            foreach ($fibonacciBetweenTimestamps as $value) {
                echo $value;
                echo "\n";
            }

            return new Response('', Response::HTTP_OK);
        }
        return new Response('No se han recibido las fechas.', Response::HTTP_OK);
    }

    /**
     * Función recursiva para las iteraciones de Fibonacci
     *
     * @param integer $num - El número a iterar.
     * @return int
     */
    function series(int $num): int {
        if ($num == 0) {
            return 0;
        } else if ($num == 1) {
            return 1;
        } else {
            return ($this->series($num - 1) + $this->series($num - 2));
        } 
    }
}
