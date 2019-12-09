<?php
namespace Models;

use Core\Model;

class Filter extends Model {
    public function getFilters($filtersSelected) {
        $restaurant = new Restaurant();
        $neighborhood = new Neighborhood();
        $paymentType = new PaymentTypes();
        $weekDay = new WeekDay();

        $data = [
            'neighborhoods' => $neighborhood->getListNeighborhoods(),
            'restaurantsByNeighborhoods' => $restaurant->getTotalRestaurantsByNeighborhoods($filtersSelected),
            'ratings' => $restaurant->getTotalRatingsByStars($filtersSelected),
            'ratingsByStars' => [
                0 => 0, // 0 Estrelas
                1 => 0, // 1 Estrela
                2 => 0, // 2 Estrelas
                3 => 0, // 3 Estrelas
                4 => 0, // 4 Estrelas
                5 => 0  // 5 Estrelas
            ],
            'ratingSelected' => (isset($filtersSelected['rating'])? $filtersSelected['rating'] : 5),
            'totalRatingsByStars' => 0,
            'restaurantsInPromotion' => $restaurant->getTotalRestaurantsInPromotion($filtersSelected),
            'weekDays' => $weekDay->getListWeekDays(),
            'restaurantsByWeekDays' => $restaurant->getTotalRestaurantsByWeekDays($filtersSelected),
            'restaurantsOpen' => $restaurant->getTotalRestaurantsOpen($filtersSelected),
            'restaurantsClosed' => $restaurant->getTotalRestaurantsClosed($filtersSelected),
            'paymentTypes' => $paymentType->getListPaymentTypes(),
            'restaurantsByPaymentTypes' => $restaurant->getTotalRestaurantsByPaymentTypes($filtersSelected),
            // 'maxFilterPrice' => $plate->getMaxPrice(),
            // 'rangePrice0' => (isset($filtersSelected['rangePrice0'])? $filtersSelected['rangePrice0'] : 0),
            // 'rangePrice1' => (isset($filtersSelected['rangePrice1'])? $filtersSelected['rangePrice1'] : 0),
            'searchTerms' => (!empty($filtersSelected['searchTerm'])? $filtersSelected['searchTerm'] : '')
        ];

        // basicamente ele faz um loop em cada bairro e adiciona uma propriedade ao array data no item 'bairros', chamada de 'count', que recebe como valor o 'total_restaurants_by_neighborhoods', calculando a quantidade de restaurantes por bairro)
        foreach ($data['neighborhoods'] as $key => $neighborhood) {
            $data['neighborhoods'][$key]['count'] = 0; // para evitar erros quando um bairro não tiver restantes cadastrados

            foreach ($data['restaurantsByNeighborhoods'] as $restaurantsByNeighborhood) {
                if($restaurantsByNeighborhood['neighborhood_id'] == $neighborhood['id_neighborhood']) {
                    $data['neighborhoods'][$key]['count'] = $restaurantsByNeighborhood['total_restaurants_by_neighborhoods'];
                }
            }

            // Remove os bairros sem restaurantes
            // if ($data['restaurants'][$key]['count'] == 0) {
            //     unset($data['restaurants'][$key]);
            // }
        }

        // Criando um filtro para as avaliações
        foreach ($data['ratingsByStars'] as $key => $item) {
            $totalRatingsByStar = 0;

            foreach ($data['ratings'] as $rating) {

                if($rating['rating'] == 0 && $key == 0) {
                    $totalRatingsByStar = $rating['total_ratings_by_star'];

                } else if($rating['rating'] > 0 && $rating['rating'] <= 1 && $key == 1) {
                    $totalRatingsByStar += $rating['total_ratings_by_star'];
                    
                } else if($rating['rating'] > 1 && $rating['rating'] <= 2 && $key == 2) {
                    $totalRatingsByStar += $rating['total_ratings_by_star'];
                    
                } else if($rating['rating'] > 2 && $rating['rating'] <= 3 && $key == 3) {
                    $totalRatingsByStar += $rating['total_ratings_by_star'];
                    
                } else if($rating['rating'] > 3 && $rating['rating'] <= 4 && $key == 4) {
                    $totalRatingsByStar += $rating['total_ratings_by_star'];
                    
                } else if($rating['rating'] > 4 && $rating['rating'] <= 5 && $key == 5) {
                    $totalRatingsByStar += $rating['total_ratings_by_star'];
                }
                
                $data['ratingsByStars'][$key] = $totalRatingsByStar;
            }            
        }

        for ($i = 0; $i <= $data['ratingSelected']; $i++) { 
            $data['totalRatingsByStars'] += $data['ratingsByStars'][$i];
        }

        foreach ($data['paymentTypes'] as $key => $paymentType) {
            $data['paymentTypes'][$key]['count'] = 0; // para evitar erros quando um bairro não tiver restantes cadastrados

            foreach ($data['restaurantsByPaymentTypes'] as $restaurantsByPaymentType) {
                if($restaurantsByPaymentType['payment_types_id'] == $paymentType['id_payment_types']) {
                    $data['paymentTypes'][$key]['count'] = $restaurantsByPaymentType['total_restaurants_by_payment_types'];
                }
            }

            // // Remove os tipos de pagamento em que nenhum restaurante é vinculado
            // if ($data['paymentTypes'][$key]['count'] == 0) {
            //     unset($data['PaymentTypes'][$key]);
            // }
        }

        foreach ($data['weekDays'] as $key => $weekDay) {
            $data['weekDays'][$key]['count'] = 0; // para evitar erros quando um dia da semana não tiver restantes cadastrados

            foreach ($data['restaurantsByWeekDays'] as $restaurantsByWeekDay) {
                if($restaurantsByWeekDay['week_day_id'] == $weekDay['id_week_day']) {
                    $data['weekDays'][$key]['count'] = $restaurantsByWeekDay['total_restaurants_by_week_days'];
                }
            }

            // Remove os dias da semana em que não tem ao menos 1 restaurantes cadastrado
            // if ($data['weekDays'][$key]['count'] == 0) {
            //     unset($data['weekDays'][$key]);
            // }
        }

        // if (empty($data['rangePrice1'])) {
        //     $data['rangePrice1'] = $data['maxFilterPrice'];
        // }
        // print_r($data); exit;

        return $data;
    }
} 