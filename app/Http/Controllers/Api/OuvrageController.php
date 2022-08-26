<?php

namespace App\Http\Controllers\Api;

use App\Models\Couche;
use App\Models\Instance;
use App\Models\Ouvrage;
use App\Models\SousThematique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OuvrageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ouvrages = Ouvrage::all();


        return $this->sendResponse($ouvrages, 'Ouvrages retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ouvrage  $ouvrage
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $ouvrage = Ouvrage::find($id);

        if (is_null($ouvrage)) {
            return $this->sendError('Ouvrage not found.');
        }

        return $this->sendResponse($ouvrage, 'ouvrage retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ouvrage  $ouvrage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ouvrage $ouvrage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ouvrage  $ouvrage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ouvrage $ouvrage)
    {
        //
    }

    public function searchBySyndicat(Request $request)
    {
        $nomsyndicat = $request->nomsyndicat;

        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)->get();

        return $this->createGeojson($ouvrages);
    }

    public function searchByDepartement(Request $request)
    {
        $nomdepartement = $request->nomdepartement;

        $ouvrages = Ouvrage::where('nomdepartement', $nomdepartement)->get();


        return $this->createGeojson($ouvrages);
    }

    public function searchByCommune(Request $request)
    {
        $nomcommune = $request->nomcommune;

        $ouvrages = Ouvrage::where('nomcommunebe', $nomcommune)

            ->orWhere('nomcommune', $nomcommune)
            ->get();


        return $this->createGeojson($ouvrages);
    }

    public function globalSearch(Request $request)
    {
        $nomsyndicat = $request->nomsyndicat;
        $nomcommune = $request->nomcommune;
        $typeeau = $request->typeeau;
        $question = $request->question;


        if ($question == "1") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)
                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('nomcommune', $nomcommune)
                            ->where('etat', 'Bon')

                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)
                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "2") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Endomagé')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Endomagé')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Endomagé')
                            ->where('nomcommune', $nomcommune)
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Endomagé')
                            ->where('nomcommune', $nomcommune)

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Endomagé')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Endomagé')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Endomagé')
                            ->where('nomcommune', $nomcommune)
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Endomagé')
                            ->where('nomcommune', $nomcommune)

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "3") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Non')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Non')
                            ->where('nomcommune', $nomcommune)
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Non')
                            ->where('nomcommune', $nomcommune)

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('fontionnel', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('fontionnel', 'Non')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)
                            ->where('fontionnel', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)
                            ->where('fontionnel', 'Non')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "4") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('fontionnel', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)


                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "5") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    } else {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('qualiteeau', 'Bonne')
                            ->where('fontionnel', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)


                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    }
                }
            }
        }

        if ($question == "6") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('qualiteeau', 'Mauvaise')
                            ->where('fontionnel', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)


                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    }
                }
            }
        }

        if ($question == "7") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->where('nomcommune', $nomcommune)


                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->where('nomcommune', $nomcommune)


                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "8") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->where('nomcommune', $nomcommune)


                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->where('nomcommune', $nomcommune)


                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "9") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    }
                }
            }
        }

        if ($question == "10") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    }
                }
            }
        }


        return $this->createGeojson($ouvrages);
    }

    public function createGeojson($datas)
    {
        $response = array();
        foreach ($datas as $data) {
            $lon = floatval(str_replace(",", ".", $data->longitude));
            $lat = floatval(str_replace(",", ".", $data->latitude));
            $geometry = [
                "type" => "Point",
                "coordinates" => [$lon, $lat]
            ];

            $response[] = [
                "type" => "Feature",
                "geometry" => $geometry,
                "properties" => $data
            ];
        }

        $name = [
            "name" => "urn:ogc:def:crs:OGC:1.3:CRS84"
        ];

        $crs = [
            "type" => "name", "properties" => $name
        ];


        $geojson = [
            "type" => "FeatureCollection",
            "name" => "sql_statement",
            "crs" =>  $crs,
            "features" => $response
        ];

        return json_encode($geojson);
    }

    public function globalSearchLocal($nomsyndicat, $nomcommune, $typeeau, $question)
    {


        if ($question == "1") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)
                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('nomcommune', $nomcommune)
                            ->where('etat', 'Bon')

                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)
                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "2") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Endomagé')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Endomagé')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Endomagé')
                            ->where('nomcommune', $nomcommune)
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Endomagé')
                            ->where('nomcommune', $nomcommune)

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Endomagé')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Endomagé')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Endomagé')
                            ->where('nomcommune', $nomcommune)
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Endomagé')
                            ->where('nomcommune', $nomcommune)

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "3") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Non')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Non')
                            ->where('nomcommune', $nomcommune)
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Non')
                            ->where('nomcommune', $nomcommune)

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('fontionnel', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('fontionnel', 'Non')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)
                            ->where('fontionnel', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)
                            ->where('fontionnel', 'Non')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "4") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('fontionnel', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)


                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "5") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    } else {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('qualiteeau', 'Bonne')
                            ->where('fontionnel', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)


                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Bonne')
                            ->get();
                    }
                }
            }
        }

        if ($question == "6") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('qualiteeau', 'Mauvaise')
                            ->where('fontionnel', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)


                            ->where('fontionnel', 'Oui')

                            ->where('typeeau', $typeeau)
                            ->where('qualiteeau', 'Mauvaise')
                            ->get();
                    }
                }
            }
        }

        if ($question == "7") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->where('nomcommune', $nomcommune)


                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->where('nomcommune', $nomcommune)


                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Formel')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "8") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->where('nomcommune', $nomcommune)


                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')

                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->where('nomcommune', $nomcommune)


                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('existencecomitegestion', 'Oui')
                            ->where('statutlegal', 'Informel')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->get();
                    }
                }
            }
        }

        if ($question == "9") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Non')
                            ->where('existencecomitegestion', 'Oui')
                            ->get();
                    }
                }
            }
        }

        if ($question == "10") {
            if ($nomsyndicat == "Tout") {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')

                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    }
                }
            } else {
                if ($nomcommune == "Tout") {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')

                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    }
                } else {
                    if ($typeeau == "Tout") {
                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    } else {

                        $ouvrages = Ouvrage::where('nomsyndicat', $nomsyndicat)
                            ->where('etat', 'Bon')
                            ->where('nomcommune', $nomcommune)



                            ->where('typeeau', $typeeau)
                            ->where('fontionnel', 'Oui')
                            ->where('existencecomitegestion', 'Non')
                            ->get();
                    }
                }
            }
        }


        return $this->createGeojson($ouvrages);
    }


    public function generateFilesRequest()
    {
        $syndicatarray = ['Tout', 'SYNCOBE', 'SYNCOMALOU', 'SYDECOMAR'];
        $communearray = ['Tout', 'BIBEMI', 'LAGDO', 'GAROUA 2', 'GAROUA 1', 'GAROUA 3', 'TOUROUA', 'BARNDAKE', 'PITOA', 'NGONG', 'GASHIGA', 'BASHEO', 'DEMBO', 'FIGUIL', 'GUIDER', 'MAYO OULO', 'MADINGRING', 'TOUBORO', 'TCHOLIRE', 'REY BOUBA'];
        $ouvrages = ['Puit', 'Forage', 'Latrines', 'Pompe'];


        for ($j = 0; $j < count($syndicatarray); $j++) {
            for ($k = 0; $k < count($communearray); $k++) {
                for ($l = 0; $l < count($ouvrages); $l++) {
                    for ($i = 1; $i <= 10; $i++) {

                        $response = $this->globalSearchLocal($syndicatarray[$j], $communearray[$k], $ouvrages[$l], $i);

                        file_put_contents(public_path() . '/datas/' . strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i)  . '.geojson', $response);

                        if ($ouvrages[$l] == 'Puit') {
                            $logo = public_path() . '/svg/puit.svg';
                        }
                        if ($ouvrages[$l] == 'Forage') {
                            $logo = public_path() . '/svg/forage.svg';
                        }
                        if ($ouvrages[$l] == 'Latrines') {
                            $logo = public_path() . '/svg/latrines.svg';
                        }
                        if ($ouvrages[$l] == 'Pompe') {
                            $logo = public_path() . '/svg/pompe.svg';
                        }

                        $data_src = public_path() . '/datas/' . strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i)  . '.geojson';



                        $input['sous_thematique_id'] = 1;
                        $input['nom'] = strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i);
                        $input['nom_en']   = strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i);
                        $input['geometry']  = 'point';
                        $input['remplir_color']   =  '#009fe3';
                        $input['contour_color'] =      '#009fe3';
                        $input['service_carto']  =  'wms';
                        $input['wms_type'] =    'data';


                        $sousThematique = SousThematique::find($input['sous_thematique_id']);
                        $schema = $sousThematique->thematique->schema . ' ' . $input['nom'];

                        $input['schema_table_name'] = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $schema));

                        $input['identifiant'] = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $input['nom']));

                        $instance = Instance::find(1);

                        $couche = Couche::create($input);

                        $qgis_project_name = $instance->nom . $sousThematique->thematique->id;

                        $response = Http::timeout(500)->post(env('CARTO_URL') . '/addotherlayer', [

                            'qgis_project_name' => $qgis_project_name,
                            'path_qgis' => '/var/www/html/src/qgis/' . $instance->nom,
                            'path_data' => '/var/www/html/src/geosm_mvp/' . $instance->nom .  $data_src,
                            'geometry' => $couche->geometry,
                            'identifiant' =>  $couche->identifiant,
                            'path_logo' => '/var/www/html/src/geosm_mvp/' . $instance->nom .  $logo,
                            'color' => $couche->remplir_color
                        ]);

                        if ($response->successful()) {
                            $status = $response->json("status");
                            $layer = $response->json("layer");
                            if ($status) {
                                $path_project = str_replace('/var/www/html/src/qgis/', '', $layer['path_project']);

                                $qgis_url = env('URL_QGIS') . $path_project;
                                $bbox = $layer['bbox'];
                                $projection = $layer['scr'];
                                $features = $layer['features'];

                                $couche->qgis_url = $qgis_url;
                                $couche->bbox = $bbox;
                                $couche->projection = $projection;
                                $couche->number_features = $features;
                                $couche->save();
                            } else {
                                echo 'Erreur lors de la création de la couche.';
                            }
                        } else {
                            echo 'Erreur lors de la création de la couche.';
                        }


                        echo 'Couche créée avec succès.';


                        /* Http::attach(
                            'logo',
                            file_get_contents(public_path() . '/' . $logo),

                        )->attach(
                            'data_src',
                            file_get_contents(public_path() . '/datas/' . strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i)  . '.geojson'),
                            strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i)  . '.geojson',

                        )->withHeaders([
                            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYjRmNjc3MDcxOTA0ODBjMzg5NDQzYjlkNjRjNTU0MDY2Yzk4MDNjYjBjYmFjODlkNjcxZjUxMTk5ZWQyNzJjYWU4OTM1YjZiZjU5ZDk2YTQiLCJpYXQiOjE2NjE1MTgxNTguODQ2NTUxLCJuYmYiOjE2NjE1MTgxNTguODQ2NTUyLCJleHAiOjE2NjQxMTAxNTguODQwNTI0LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.BPataVNvHDN80lP5GsGdZ6JbPNS6VknEUO3TraTF8YrrBpj8dEJOa_MKGftFHMO-nZEsw1sdWAkYocehlLDIk9iB7nDrH3nFtacifTGxhQC7UvXCg_ob5Tq63CaggQyBX-QXI5hyKVS02Wncq1Jzt7dBp70Ewba8ZODtJ-CU37W4PlFNj52zeqNQsOZKz-KwMt37YCZAaYMSpy--06KaxXfK3JH_-YUWaYVytDrBgmpD7433dNLaIVCjnlFlPwDilkVnSC57JuDOjoBszshKDf9WDoz4WtdWgYnYne_ub85po_HPcOCMKlNvyUEiPYsK6xMZ9w708TH7oG9yziVxQzof9jU2mN8C1mzwd9ymDaZKZ-UfGjy8YFwOHaanmS4mjyenySVkA5j_WJo5REXC3sh9h0ksJzWwy7CBmMUcz5pceJPJNoncOJxuz3BAPY5WGoHaMcThoopJKKt7VrpTf9F9oezksjGnhBcrxkCI5sVXp4szPC2pQggm2JafOHkGqaZUCiUv1QKeqxwcVlUwWFAB5tw8kU9Z5a8dxhJClnwBOXO_A7wcOSMeehcBGLtviZK2_4wJpARSTAR4fH1kzKERWgdej_DilT6T991HLrg_G4hV9NuDrm9w-zijn0xbFhfNOWlz1TaRBhQPaE2l7oTlQoZ7FaOlosf0S2eJ3YU',
                            'X-Authorization' => '4rqxvQTkRzD56ZYFepB7AxPddrj24yVKHcNxHfk8eqRirN0WHeOwKLP2vCFk6jEj',
                            'Content-Type' => 'multipart/form-data',
                            'Accept' => 'application/json'
                        ])->timeout(500)->post('https://pradecservices.geosm.org/api/couches', [
                            'sous_thematique_id' => 1,
                            'nom' => strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i),
                            'nom_en' => strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i),
                            'geometry' => 'point',
                            'remplir_color' =>  '#009fe3',
                            'contour_color' =>  '#009fe3',
                            'service_carto' =>  'wms',
                            'wms_type' =>  'data',
                        ]);*/
                    }
                }
            }
        }
    }
}
