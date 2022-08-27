<?php

namespace App\Http\Controllers\Api;

use App\Models\Couche;
use App\Models\Instance;
use App\Models\Metadata;
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


        $geojson = [
            "type" => "FeatureCollection",
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

                        $data = json_decode($response, true);

                        if (count($data['features']) > 0) {
                            file_put_contents(public_path() . '/datas/' . str_replace(' ', '', strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i))   . '.geojson', $response);

                            if ($ouvrages[$l] == 'Puit') {
                                $logo =  '/svg/puit.svg';
                            }
                            if ($ouvrages[$l] == 'Forage') {
                                $logo =  '/svg/forage.svg';
                            }
                            if ($ouvrages[$l] == 'Latrines') {
                                $logo =  '/svg/latrines.svg';
                            }
                            if ($ouvrages[$l] == 'Pompe') {
                                $logo =  '/svg/pompe.svg';
                            }

                            $data_src = public_path() . '/datas/' . str_replace(' ', '', strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i))  . '.geojson';



                            $input['sous_thematique_id'] = 1;
                            $input['nom'] = str_replace(' ', '', strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i));
                            $input['nom_en']   = str_replace(' ', '', strtolower($syndicatarray[$j] . $communearray[$k] . $ouvrages[$l] . 'q' . $i));
                            $input['geometry']  = 'point';
                            $input['remplir_color']   =  '#009fe3';
                            $input['contour_color'] =      '#009fe3';
                            $input['service_carto']  =  'wfs';
                            $input['wms_type'] =    'data';
                            $input['logo'] = $logo;


                            $sousThematique = SousThematique::find($input['sous_thematique_id']);
                            $schema = $sousThematique->thematique->schema . ' ' . $input['nom'];

                            $input['schema_table_name'] = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $schema));

                            $input['identifiant'] = strtolower(preg_replace('/[^A-Za-z0-9]/', '', $input['nom']));

                            $couche = Couche::create($input);

                            $couche->identifiant = $input['identifiant'];
                            $couche->qgis_url = 'https://qgis.geosm.org/ows?map=pradec/pradec.qgz';

                            $couche->number_features = count($data['features']);
                            $couche->save();
                        }

                        /*   */
                    }
                }
            }
        }
    }

    public function addMetadatas()
    {
        $input['epsg'] = "EPSG:4326";

        for ($i = 1; $i <= 641; $i++) {
            $input['couche_id'] = $i;

            $metadata = Metadata::create($input);
        }
    }
}
