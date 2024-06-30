<?php
			
			$sms_add = "Erreur! Données incomplètes";
			$sms_get = "Erreur! Données incomplètes";
			$response = array
			(
				"error_add" => true, "error_msg_add" => $sms_add,
				"reqString_get" => "Erreur", "reqArray_get" => "Erreur",
				"error_get" => true, "error_msg_get" => $sms_get
			);
			/*
			$_POST["typeReqAdd"] = array($c_constantes->REQ_INSERT);
            $_POST["typeReqCheck"] = array($c_constantes->REQ_GET_BY_ARRAY);
            $_POST["bddType"] = 0;
            $_POST["tblName"] = "mazoughou_qcm_membre";
            $_POST["idOffLineIndex"] = 0;
            $_POST["idOnLineIndex"] = 0;
            $_POST["labelIndex"] = 1;
            $_POST["idParentIndex"] = 2;
            $_POST["singleEntryIndex"] = 1;
            $_POST["dualEntryIndex"] = 0;
            $_POST["reqGroup"] = "";
            $_POST["reqOrder"] = "";
            $_POST["reqLimit"] = "";
            $_POST["tbl_rowLabel"] = array("id", "E_Mail", "Telephone", "Prenom",
                    "Nom", "Sexe", "unique_id", "emailSecour", "Mot_de_Passe",
                    "dateNaissance", "encrypted_password", "Statut", "connecte", "salt",
                    "Date_Inscription");
            $_POST["tbl_rowType"] = array("INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,", "VARCHAR(255) NOT NULL,", "VARCHAR(255) NOT NULL,", "VARCHAR(255) NOT NULL,",
                    "VARCHAR(255) NOT NULL,", "VARCHAR(255) NOT NULL,", "VARCHAR(255) NOT NULL,", "VARCHAR(255) NOT NULL", "VARCHAR(255) NOT NULL",
                    "DATE NOT NULL,", "VARCHAR(255) NOT NULL,", "VARCHAR(255) NOT NULL,", "VARCHAR(255) NOT NULL", "VARCHAR(255) NOT NULL",
                    "DATETIME NOT NULL"
            );
            $_POST["typeReqGet"] = $c_constantes->REQ_GET_BY_ARRAY;
            $idEtudiant=70;
            $_POST["tbl_rowValue_search"] = array
            (
                    "mazoughou@magoe.gn"
            );
			$_POST["tbl_rowValue_add"] = array
            (
                    array("0", "Bien", $idEtudiant, "Positive", "mazoughou@magoe.gn",
                    "dateSav")
            );
			*/
			if(isset($_POST["tbl_rowLabel"])&&isset($_POST["tbl_rowType"])&&isset($_POST["tblName"])&&isset($_POST["bddType"])
				&&isset($_POST["idOffLineIndex"])&&isset($_POST["idOnLineIndex"])&&isset($_POST["labelIndex"])
				&&isset($_POST["idParentIndex"])&&isset($_POST["singleEntryIndex"])&&isset($_POST["dualEntryIndex"])
				&&isset($_POST["reqGroup"])&&isset($_POST["reqOrder"])&&isset($_POST["reqLimit"])
				)
			{
				$bddType = $_POST["bddType"];
				$tbl_rowLabel = $_POST["tbl_rowLabel"];
				$tbl_rowType = $_POST["tbl_rowType"];
				$tblName = $_POST["tblName"];
				$idOffLineIndex = $_POST["idOffLineIndex"];
				$idOnLineIndex = $_POST["idOnLineIndex"];
				$labelIndex = $_POST["labelIndex"];
				$idParentIndex = $_POST["idParentIndex"];
				$singleEntryIndex = $_POST["singleEntryIndex"];
				$dualEntryIndex = $_POST["dualEntryIndex"];
				$reqGroup = $_POST["reqGroup"];
				$reqOrder = $_POST["reqOrder"];
				$reqLimit = $_POST["reqLimit"];
				if(isset($_POST["typeReqCheck"])&&isset($_POST["typeReqAdd"])&&isset($_POST["tbl_rowValue_add"])
					&& $tblName!="mazoughou_qcm_membre" && $tblName!="t_matiere_univ")
				{
					$tbl_listetypeReqCheck = $_POST["typeReqCheck"];
					$tbl_listetypeReqAdd = $_POST["typeReqAdd"];
					$tbl_listeValue = $_POST["tbl_rowValue_add"];
					for($numValue=0; $numValue<sizeof($tbl_listeValue); $numValue++)
					{
						$typeReqCheck = $tbl_listetypeReqCheck[$numValue];
						$typeReqAdd = $tbl_listetypeReqAdd[$numValue];
						$tbl_rowValue = $tbl_listeValue[$numValue];
						
						$isAllowed = true;
						$contenuMessage = "";
						if($tblName==$c_table->TABLE_PARENT_ELEVE["tblName"])
						{
							$isAllowed = false;
							$visiteur1 = $bdd->prepare
							("
								SELECT
									t_inscrit_etudiant.idEtudiant AS idEtudiant, t_inscrit_etudiant.matricul AS matricul,
										t_inscrit_etudiant.emailMagoeEtudiant AS emailMagoeEtudiant
								FROM t_inscrit_etudiant
								INNER JOIN mazoughou_qcm_membre ON t_inscrit_etudiant.emailMagoeEtudiant = mazoughou_qcm_membre.E_Mail
								INNER JOIN t_niveau_univ ON t_inscrit_etudiant.idNiveau = t_niveau_univ.idLibelle
								INNER JOIN t_profil_univ ON t_niveau_univ.idProfil = t_profil_univ.idProfilUniv
								INNER JOIN ecoletable ON t_profil_univ.idEcoleMere = ecoletable.idEcole
								WHERE t_inscrit_etudiant.emailMagoeEtudiant=? AND t_profil_univ.idEcoleMere=?
									t_inscrit_etudiant.emailMagoeEtudiant AND NOT IN('".implode("', '", $c_table->ARRAY_EMAIL_NOT_USE)."')
							"); 
							$visiteur1->execute(array($tbl_rowValue[3], $tbl_rowValue[8]));
							if ($resultat1 = $visiteur1->fetch())
							{
								$isAllowed = true;
								
								$contenuMessage = "Vous avez été ajouté comme parent(e) de ".$resultat1["nomEtudiant"]." ".$resultat1["prenomEtudiant"]."
										en qualité de ".$tbl_rowValue[6];
							}
							else
							{
								$visiteur2 = $bdd->prepare
								("
									SELECT
										t_inscrit_etudiant.idEtudiant AS idEtudiant, t_inscrit_etudiant.matricul AS matricul,
											t_inscrit_etudiant.emailMagoeEtudiant AS emailMagoeEtudiant
									FROM t_inscrit_etudiant
									INNER JOIN t_niveau_univ ON t_inscrit_etudiant.idNiveau = t_niveau_univ.idLibelle
									INNER JOIN t_profil_univ ON t_niveau_univ.idProfil = t_profil_univ.idProfilUniv
									INNER JOIN ecoletable ON t_profil_univ.idEcoleMere = ecoletable.idEcole
									WHERE t_inscrit_etudiant.matricul=? AND t_profil_univ.idEcoleMere=?
								"); 
								$visiteur2->execute(array($tbl_rowValue[7], $tbl_rowValue[8]));
								if ($resultat1 = $visiteur2->fetch())
								{
									$isAllowed = true;
								
									$contenuMessage = "Vous avez été ajouté comme parent(e) de ".$resultat1["nomEtudiant"]." ".$resultat1["prenomEtudiant"]."
										en qualité de ".$tbl_rowValue[6];
								}
								$visiteur2->closeCursor();
							}
							$visiteur1->closeCursor();
						}
						
						if($typeReqAdd == $c_constantes->REQ_INSERT && $tbl_rowValue[0]==0)
						{
							$response_checkAdd_2 = $c_load_data->data_2($typeReqCheck, $tbl_rowLabel, $tbl_rowType,
										   $tbl_rowValue, $tblName, $idOffLineIndex,
										   $idOnLineIndex, $labelIndex, $idParentIndex,
										   $singleEntryIndex, $dualEntryIndex,
										   $reqGroup, $reqOrder, $reqLimit, $bddType, $bdd, $bdd1);
							if($response_checkAdd_2["error"])
							{
								$reponseAdd = $c_load_data->insertData($typeReqAdd, $_POST["tbl_rowLabel"], $_POST["tbl_rowType"],
												   $tbl_rowValue, $_POST["tblName"], $_POST["bddType"], $bdd, $bdd1);
												   
								$sms_add = $reponseAdd["error_msg"];
								$response["error_add"] = $reponseAdd["error"];
							}
							else
							{
								$sms_add = "Erreur! Cette valeur existe déjà";
							}
						}
						else if($typeReqAdd != $c_constantes->REQ_INSERT)
						{
							$response_checkAdd = $c_load_data->data_2($c_constantes->REQ_GET_BY_SINGLE_ENTY, $tbl_rowLabel, $tbl_rowType,
										   $tbl_rowValue, $tblName, 0, 0, 0, 0, 0, 0, "", "", "", "", $bdd, $bdd1);
							if($response_checkAdd["error"])
							{
									$sms_add = "Erreur! Ce champ n'existe pas";
							}
							else
							{
								$c_load_data->insertData($typeReqAdd, $_POST["tbl_rowLabel"], $_POST["tbl_rowType"],
												   $tbl_rowValue, $_POST["tblName"], $_POST["bddType"], $bdd, $bdd1);
												   
								$sms_add = "OK";
								$response["error_add"] = false;
							}
						}
						if($sms_add=="OK")
						{
							if($tblName==$c_table->TABLE_PARENT_ELEVE["tblName"])
							{
								$objetMessage = "Vous avez été ajouté comme parent d'élève ou d'étudiant";
								$emailEditeur = $tbl_rowValue[9];
								$emailDestinataire = $tbl_rowValue[1];
								
								sendMessage($objetMessage, $contenuMessage, $emailEditeur, $emailDestinataire);
							}
							else if($tblName==$c_table->TABLE_NAME_EVALUATION&&isset($_SESSION['E_Mail_QCM'])
								&& isset($_POST["labelMatiere"])&& isset($_POST["idNiveau"]))
							{
								$rangEvaluation = $tbl_rowValue[1];
								$idMatiere = $tbl_rowValue[2];
								$anneeUniv = $tbl_rowValue[3];
								$groupePedagogique = $tbl_rowValue[4];
								$dateEval1 = $tbl_rowValue[5];
								$debutEval1 = $tbl_rowValue[6];
								$finEval1 = $tbl_rowValue[7];
								$dateEval2 = $tbl_rowValue[8];
								$debutEval2 = $tbl_rowValue[9];
								$finEval2 = $tbl_rowValue[10];
								$idNiveau = $_POST["idNiveau"];
								$labelMatiere = $_POST["labelMatiere"];
								
								if($typeReqAdd==$c_constantes->REQ_INSERT)
								{
									$objetMessage = " Une évaluation a été programmée en ".$labelMatiere;
								}
								else if($typeReqAdd==$c_constantes->REQ_UPDATE)
								{
									$objetMessage = " Une évaluation a été modifiée en ".$labelMatiere;
								}
								else if($typeReqAdd==$c_constantes->REQ_DELETE)
								{
									$objetMessage = " Une évaluation a été supprimée en ".$labelMatiere;
								}
								$contenuMessage = "<br><a href='".$rootLink."/qcmBoite_2.php?souvh%C3%A8=evalProgrammee&eval_note=eyei&idMatiere=".$idMatiere."'>
									".$objetMessage." (clique ici pour accedez)</a><br><br>
									<table style='background-color: black; border-collapse: collapse;'>
										<caption>
											<strong>
												".
												$rangEvaluation." en ".$labelMatiere."<br>".
												$groupePedagogique
												."
											</strong>
										</caption>
										<tr>
											<td class='cell'></td>
											<td class='cell'>Date</td>
											<td class='cell'>Debut</td>
											<td class='cell'>Fin</td>
										</tr>
										<tr>
											<td class='cell'>Premier tour</td>
											<td class='cel'>
												".$dateEval1."
											</td>
											<td class='cel' style='text-align:righ'>
												".$debutEval1."
											</td>
											<td class='cel' style='text-align:righ'>
												".$finEval1."
											</td>
										</tr>
										<tr>
											<td class='cell'>Deuxième tour</td>
											<td class='cel'>
												".$dateEval2."
											</td>
											<td class='cel' style='text-align:righ'>
												".$debutEval2."
											</td>
											<td class='cel' style='text-align:righ'>
												".$finEval2."
											</td>
										</tr>
									</table>";
								//Récupération des emails des élèves
								$email = $bdd->prepare
								('
									SELECT
										t_inscrit_etudiant.emailMagoeEtudiant AS emailMagoeEtudiant,
										mazoughou_qcm_membre.emailSecour AS emailSecour
									FROM t_inscrit_etudiant
									INNER JOIN mazoughou_qcm_membre ON t_inscrit_etudiant.emailMagoeEtudiant = mazoughou_qcm_membre.E_Mail
									WHERE t_inscrit_etudiant.idNiveau=? AND t_inscrit_etudiant.anneeUniv=? AND t_inscrit_etudiant.groupePedag=?
									GROUP BY t_inscrit_etudiant.emailMagoeEtudiant
									LIMIT 0, 50
								');
								$email->execute(array($idNiveau, $anneeUniv, $groupePedagogique));
								while($emailTrouv = $email->fetch())
								{
									//$emailSecour = $emailTrouv["emailSecour"];
									$emailEditeur = $_SESSION['E_Mail_QCM'];
									$emailDestinataire = $emailTrouv["emailMagoeEtudiant"];
									
									sendMessage($objetMessage, $contenuMessage, $emailEditeur, $emailDestinataire, $bdd);
								}
							}
						}
					
					}
					/*$numToSee = 2;
					$sms_add = $tbl_listeValue[$numToSee][$idOnLineIndex]." | ".$tbl_listeValue[$numToSee][$labelIndex].
						" | ".$tbl_listeValue[$numToSee][$idParentIndex]." | ".$tbl_listeValue[$numToSee][$singleEntryIndex].
						" | ".$tbl_listeValue[$numToSee][$dualEntryIndex];*/
				}
				if(isset($_POST["typeReqGet"])&&isset($_POST["tbl_rowValue_search"]))
				{
					$typeReqGet = $_POST["typeReqGet"];
					$nbrItems = 0;
					$tbl_rowValue = $_POST["tbl_rowValue_search"];
					$response_get = $c_load_data->data_2($typeReqGet, $tbl_rowLabel, $tbl_rowType,
										   $tbl_rowValue, $tblName, $idOffLineIndex,
										   $idOnLineIndex, $labelIndex, $idParentIndex,
										   $singleEntryIndex, $dualEntryIndex,
										   $reqGroup, $reqOrder, $reqLimit, $bddType, $bdd, $bdd1);
					if(!$response_get["error"])
					{
							for($numData=0; $numData<sizeof($response_get["items"]); $numData++)
							{
								$response["item"][] = $response_get["items"][$numData];
							}
							$nbrItems = sizeof($response_get["items"]);
						
						$sms_get = "OK";
						$response["error_get"] = false;
					}
					else
					{
						$sms_get = $response_get["error_msg"];
						$response["error_get"] = $response_get["error"];
					}
					$response["reqString_get"] = $response_get["reqString"];
					$response["reqArray_get"] = $response_get["reqArray"];
					//$sms_get = "Select * from ".$tblName." where ".$tbl_rowLabel[$singleEntryIndex]." = ".$tbl_rowValue[0];
				}
			}
			
			$response["error_msg_add"] = $sms_add;
			$response["error_msg_get"] = $sms_get;
			$response["error"] = $response["error_get"];
		
		header('Content-type: application/json;charset=UTF-8');
		print json_encode($response, JSON_PRETTY_PRINT);
