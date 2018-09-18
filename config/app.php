<?php

return [
    
            'schedule' => [   
                            'pagi' => [
                                        'start' => env('PAGI_START', '01:00'),
                                        'end' => env('PAGI_END', '03:00'),
                                    ],
                            'sore' => [
                                        'start' => env('SORE_START', '14:00'),
                                        'end' => env('SORE_END', '17:00'),
                                    ],
                            'malam' => [
                                        'start' => env('MALAM_START', '21:00'),
                                        'end' => env('MALAM_END', '23:00'),
                                    ],

                        ],

                        //utk shecdule disini tidak dipakai, yg dipakai yg di config/databaase


];
