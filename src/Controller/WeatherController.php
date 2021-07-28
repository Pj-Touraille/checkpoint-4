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

        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($request->request->get('form')['City'])) {
                $errors[] = 'Please fill out the text area';
            } else {
                $city = ucfirst(strtolower(htmlspecialchars(trim($request->request->get('form')['City']))));
            }
        }

        try {
            $results = $weatherService->getWeather($city ?? 'Toulouse', $request->getLocale(), $this->getParameter('api_key'));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $this->render('weather/index.html.twig', [
            'weather' => $results['forecast'] ?? [],
            'data' => $results['actual'] ?? [],
            'historicalWeather' => $results['history'] ?? [],
            'country' => $results['country'] ?? [],
            'form' => $form->createView()
        ]);
    }
}
