<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\WeatherService;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{_locale<%app.supported_locales%>}", name="weather_")
 */
class WeatherController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, WeatherService $weatherService)
    {
        $form = $this->createFormBuilder(null, [])
            ->setMethod("POST")
            ->add('City', TextType::class, [
                "label" => false,
                'attr' => [
                    "placeholder" => $request->getLocale() === "fr" ? "Entrer une ville ..." : "Enter a city name ...",
                ]
            ])
            ->getForm();
        $form->handleRequest($request);

        
        // Vérification de l'existence de la request en methode POST
        // if ($request->isMethod('post')) {
        if ($form->isSubmitted() && $form->isValid()) {
            // Validation simple des données
            if (empty($request->request->get('form')['City'])) {
                $errors[] = 'Please fill out the text area';
            } else {
                $city = ucfirst(strtolower(htmlspecialchars(trim($request->request->get('form')['City']))));
            }
        }
        try {
            $results = $weatherService->getWeather($city ?? 'Toulouse', $request->getLocale(), $this->getParameter('api_key'));
            // $results = ['', '', '', ''];
        } catch (\Exception $e) {
            echo $e->getMessage();
            $results = ['', '', '', ''];
        }


        return $this->render('weather/index.html.twig', [
            'weather' => $results[0],
            'data' => $results[1],
            'historicalWeather' => $results[2],
            'country' => $results[3],
            'form' => $form->createView()
        ]);
    }
}
