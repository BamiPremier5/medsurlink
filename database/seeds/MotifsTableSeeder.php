<?php

use Illuminate\Database\Seeder;

class MotifsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $motifs = array(
            array("reference"=>"-30","description"=>"Ex médical/bilan santé détaillé"),
            array("reference"=>"-31","description"=>"Ex médical/bilan santé partiel"),
            array("reference"=>"-32","description"=>"Test de sensibilité"),
            array("reference"=>"-33","description"=>"Ex microbiologique/immunologique"),
            array("reference"=>"-34","description"=>"Autre analyse de sang"),
            array("reference"=>"-35","description"=>"Autre analyse d'urine"),
            array("reference"=>"-36","description"=>"Autre analyse de selles"),
            array("reference"=>"-37","description"=>"Cytologie/histologie"),
            array("reference"=>"-38","description"=>"Autre analyse de laboratoire"),
            array("reference"=>"-39","description"=>"Epreuve fonctionnelle"),
            array("reference"=>"-40","description"=>"Endoscopie"),
            array("reference"=>"-41","description"=>"Radiologie diagnostique/imagerie"),
            array("reference"=>"-42","description"=>"Tracé électrique"),
            array("reference"=>"-43","description"=>"Autre procédure diagnostique"),
            array("reference"=>"-44","description"=>"Vaccination/médication préventive"),
            array("reference"=>"-45","description"=>"Recom./éducation santé/avis/régime"),
            array("reference"=>"-46","description"=>"Discussion entre dispensateurs SSP"),
            array("reference"=>"-47","description"=>"Discussion dispensateur spécialiste"),
            array("reference"=>"-48","description"=>"Clarification de la demande du patient"),
            array("reference"=>"-49","description"=>"Autre procédure préventive"),
            array("reference"=>"-50","description"=>"Médication/prescription/injection"),
            array("reference"=>"-51","description"=>"Incision/drainage/aspiration"),
            array("reference"=>"-52","description"=>"Excision/biopsie/cautér/débridation"),
            array("reference"=>"-53","description"=>"Perfusion/intubat./dilatat./appareillage"),
            array("reference"=>"-54","description"=>"Répar/fixation/suture/plâtre/prothèse"),
            array("reference"=>"-55","description"=>"Traitement local/infiltration"),
            array("reference"=>"-56","description"=>"Pansement/compression/bandage"),
            array("reference"=>"-57","description"=>"Thérapie manuelle/médecine physique"),
            array("reference"=>"-58","description"=>"Conseil thérap/écoute/examens"),
            array("reference"=>"-59","description"=>"Autres procédures thérapeutiques"),
            array("reference"=>"-60","description"=>"Résultats analyses/examens"),
            array("reference"=>"-61","description"=>"Résultats ex/procéd autre dispensateur"),
            array("reference"=>"-62","description"=>"Contact administratif"),
            array("reference"=>"-63","description"=>"Rencontre de suivi"),
            array("reference"=>"-64","description"=>"Epis. nouveau/en cours init. par disp."),
            array("reference"=>"-65","description"=>"Epis. nouveau/en cours init. par tiers"),
            array("reference"=>"-66","description"=>"Référence à dispens. SSP non médecin"),
            array("reference"=>"-67","description"=>"Référence à médecin"),
            array("reference"=>"-68","description"=>"Autre référence"),
            array("reference"=>"-69","description"=>"Autres procédures"),

            array("reference"=>"A01","description"=>"Douleur générale/de sites multiples"),
            array("reference"=>"A02","description"=>"Frissons"),
            array("reference"=>"A03","description"=>"Fièvre"),
            array("reference"=>"A04","description"=>"Fatigue/faiblesse générale"),
            array("reference"=>"A05","description"=>"Sensation d'être malade"),
            array("reference"=>"A06","description"=>"Evanouissement/syncope"),
            array("reference"=>"A07","description"=>"Coma"),
            array("reference"=>"A08","description"=>"Gonflement"),
            array("reference"=>"A09","description"=>"Problème de transpiration"),
            array("reference"=>"A10","description"=>"Saignement/hémorragie"),
            array("reference"=>"A11","description"=>"Douleur thoracique"),
            array("reference"=>"A13","description"=>"Préoc. par/peur traitement médical"),
            array("reference"=>"A16","description"=>"Nourrisson irritable"),
            array("reference"=>"A18","description"=>"Préoc. par son aspect extérieur"),
            array("reference"=>"A20","description"=>"Demande/discussion sur l'euthanasie"),
            array("reference"=>"A21","description"=>"Facteur de risque de cancer"),
            array("reference"=>"A23","description"=>"Facteur de risque"),
            array("reference"=>"A25","description"=>"Peur de la mort, de mourir"),
            array("reference"=>"A26","description"=>"Peur du cancer"),
            array("reference"=>"A27","description"=>"Peur d'une autre maladie"),
            array("reference"=>"A28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"A29","description"=>"Autre Symptôme/Plainte général"),

            array("reference"=>"D01","description"=>"Douleur/crampes abdominales gén."),
            array("reference"=>"D02","description"=>"Douleur abdominale/épigastrique"),
            array("reference"=>"D03","description"=>"Brûlure/brûlant/brûlement estomac"),
            array("reference"=>"D04","description"=>"Douleur rectale/anale"),
            array("reference"=>"D05","description"=>"Démangeaisons périanales"),
            array("reference"=>"D06","description"=>"Autre douleur abdominale loc."),
            array("reference"=>"D07","description"=>"Dyspepsie/indigestion"),
            array("reference"=>"D08","description"=>"Flatulence/gaz/renvoi"),
            array("reference"=>"D09","description"=>"Nausée"),
            array("reference"=>"D10","description"=>"Vomissement"),
            array("reference"=>"D11","description"=>"Diarrhée"),
            array("reference"=>"D12","description"=>"Constipation"),
            array("reference"=>"D13","description"=>"Jaunisse"),
            array("reference"=>"D14","description"=>"Hématémèse/vomissement de sang"),
            array("reference"=>"D15","description"=>"Méléna"),
            array("reference"=>"D16","description"=>"Saignement rectal"),
            array("reference"=>"D17","description"=>"Incontinence rectale"),
            array("reference"=>"D18","description"=>"Modification selles/mouvem. intestin"),
            array("reference"=>"D19","description"=>"Symptôme/Plainte dents/gencives"),
            array("reference"=>"D20","description"=>"Symptôme/Plainte bouche/langue/lèvres"),
            array("reference"=>"D21","description"=>"Problème de déglutition"),
            array("reference"=>"D23","description"=>"Hépatomégalie"),
            array("reference"=>"D24","description"=>"Masse abdominale"),
            array("reference"=>"D25","description"=>"Distension abdominale"),
            array("reference"=>"D26","description"=>"Peur du cancer du syst. digestif"),
            array("reference"=>"D27","description"=>"Peur d’une autre maladie digestive"),
            array("reference"=>"D28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"D29","description"=>"Autre Symptôme/Plainte du syst. Digestif"),

            array("reference"=>"F01","description"=>"Oeil douloureux"),
            array("reference"=>"F02","description"=>"Oeil rouge"),
            array("reference"=>"F03","description"=>"Ecoulement de l’œil"),
            array("reference"=>"F04","description"=>"Taches visuelles/flottantes"),
            array("reference"=>"F05","description"=>"Autre perturbation de la vision"),
            array("reference"=>"F13","description"=>"Sensation oculaire anormale"),
            array("reference"=>"F14","description"=>"Mouvements oculaires anormaux"),
            array("reference"=>"F15","description"=>"Apparence anormale de l’œil"),
            array("reference"=>"F16","description"=>"Symptôme/Plainte de la paupière"),
            array("reference"=>"F17","description"=>"Symptôme/Plainte lunettes"),
            array("reference"=>"F18","description"=>"Symptôme/Plainte lentilles de contact"),
            array("reference"=>"F27","description"=>"Peur d’une maladie de l’œil"),
            array("reference"=>"F28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"F29","description"=>"Autre Symptôme/Plainte de l’œil"),

            array("reference"=>"H01","description"=>"Douleur d'oreille/otalgie"),
            array("reference"=>"H02","description"=>"Problème d'audition"),
            array("reference"=>"H03","description"=>"Acouphène/bourdonnement d'oreille"),
            array("reference"=>"H04","description"=>"Ecoulement de l'oreille"),
            array("reference"=>"H05","description"=>"Saignement de l'oreille"),
            array("reference"=>"H13","description"=>"Sensation d'oreille bouchée"),
            array("reference"=>"H15","description"=>"Préoc. par l'aspect des oreilles"),
            array("reference"=>"H27","description"=>"Peur d’une maladie de l'oreille"),
            array("reference"=>"H28","description"=>"Limitation de la fonction/incap"),
            array("reference"=>"H29","description"=>"Autre Symptôme/Plainte de l'oreille"),

            array("reference"=>"K01","description"=>"Douleur cardiaque"),
            array("reference"=>"K02","description"=>"Oppression/constriction cardiaque"),
            array("reference"=>"K03","description"=>"Douleur cardiovasculaire"),
            array("reference"=>"K04","description"=>"Palpitat./perception battements card."),
            array("reference"=>"K05","description"=>"Autre battement cardiaque irrégulier"),
            array("reference"=>"K06","description"=>"Veines proéminentes"),
            array("reference"=>"K07","description"=>"Oedème, gonflement des chevilles"),
            array("reference"=>"K22","description"=>"Facteur risque mal. cardio-vasculaire"),
            array("reference"=>"K24","description"=>"Peur d’une maladie de cœur"),
            array("reference"=>"K25","description"=>"Peur de l'hypertension"),
            array("reference"=>"K27","description"=>"Peur autre maladie cardio-vasculaire"),
            array("reference"=>"K28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"K29","description"=>"Autre Symptôme/Plainte cardiovasculaire"),

            array("reference"=>"L01","description"=>"Symptôme/Plainte du cou"),
            array("reference"=>"L02","description"=>"Symptôme/Plainte du dos"),
            array("reference"=>"L03","description"=>"Symptôme/Plainte des lombes"),
            array("reference"=>"L04","description"=>"Symptôme/Plainte du thorax"),
            array("reference"=>"L05","description"=>"Symptôme/Plainte du flanc et du creux axillaire"),
            array("reference"=>"L07","description"=>"Symptôme/Plainte de la mâchoire"),
            array("reference"=>"L08","description"=>"Symptôme/Plainte de l'épaule"),
            array("reference"=>"L09","description"=>"Symptôme/Plainte du bras"),
            array("reference"=>"L10","description"=>"Symptôme/Plainte du coude"),
            array("reference"=>"L11","description"=>"Symptôme/Plainte du poignet"),
            array("reference"=>"L12","description"=>"Symptôme/Plainte de la main et du doigt"),
            array("reference"=>"L13","description"=>"Symptôme/Plainte de la hanche"),
            array("reference"=>"L14","description"=>"Symptôme/Plainte de la jambe et de la cuisse"),
            array("reference"=>"L15","description"=>"Symptôme/Plainte du genou"),
            array("reference"=>"L16","description"=>"Symptôme/Plainte de la cheville"),
            array("reference"=>"L17","description"=>"Symptôme/Plainte du pied et de l'orteil"),
            array("reference"=>"L18","description"=>"Douleur musculaire"),
            array("reference"=>"L19","description"=>"Symptôme/Plainte musculaire"),
            array("reference"=>"L20","description"=>"Symptôme/Plainte d'une articulation"),
            array("reference"=>"L26","description"=>"Peur cancer syst. ostéo-articulaire"),
            array("reference"=>"L27","description"=>"Peur autre maladie syst. ostéo-articul."),
            array("reference"=>"L28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"L29","description"=>"Autre Symptôme/Plainte ostéo-articulaire"),

            array("reference"=>"N01","description"=>"Mal de tête"),
            array("reference"=>"N03","description"=>"Douleur de la face"),
            array("reference"=>"N04","description"=>"Jambes sans repos"),
            array("reference"=>"N05","description"=>"Fourmillements doigts, pieds, orteils"),
            array("reference"=>"N06","description"=>"Autre perturbation de la sensibilité"),
            array("reference"=>"N07","description"=>"Convulsion/crise comitiale"),
            array("reference"=>"N08","description"=>"Mouvements involontaires anormaux"),
            array("reference"=>"N16","description"=>"Perturbation du goût/de l'odorat"),
            array("reference"=>"N17","description"=>"Vertige/étourdissement"),
            array("reference"=>"N18","description"=>"Paralysie/faiblesse"),
            array("reference"=>"N19","description"=>"Trouble de la parole"),
            array("reference"=>"N26","description"=>"Peur d'un cancer neurologique"),
            array("reference"=>"N27","description"=>"Peur d’une autre maladie neurologique"),
            array("reference"=>"N28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"N29","description"=>"Autre Symptôme/Plainte neurologique"),

            array("reference"=>"P01","description"=>"Sensation anxiété/nervosité/tension"),
            array("reference"=>"P02","description"=>"Réaction de stress aiguë"),
            array("reference"=>"P03","description"=>"Sensation de dépression"),
            array("reference"=>"P04","description"=>"Sentiment/comport. irritable/colère"),
            array("reference"=>"P05","description"=>"Sensation vieux, comportement sénile"),
            array("reference"=>"P06","description"=>"Perturbation du sommeil"),
            array("reference"=>"P07","description"=>"Diminution du désir sexuel"),
            array("reference"=>"P08","description"=>"Diminution accomplissement sexuel"),
            array("reference"=>"P09","description"=>"Préoccupation sur identité sexuelle"),
            array("reference"=>"P10","description"=>"Bégaiement, bredouillement, tic"),
            array("reference"=>"P11","description"=>"Trouble de l'alimentation de l'enfant"),
            array("reference"=>"P12","description"=>"Enurésie"),
            array("reference"=>"P13","description"=>"Encoprésie"),
            array("reference"=>"P15","description"=>"Alcoolisme chronique"),
            array("reference"=>"P16","description"=>"Alcoolisation aiguë"),
            array("reference"=>"P17","description"=>"Usage abusif du tabac"),
            array("reference"=>"P18","description"=>"Usage abusif de médicament"),
            array("reference"=>"P19","description"=>"Usage abusif de drogue"),
            array("reference"=>"P20","description"=>"Perturbation de la mémoire"),
            array("reference"=>"P22","description"=>"Symptôme/Plainte du comportement de l'enfant"),
            array("reference"=>"P23","description"=>"Symptôme/Plainte du comportement de l'adolescent"),
            array("reference"=>"P24","description"=>"Problème spécifique de l'apprentissage"),
            array("reference"=>"P25","description"=>"Problèmes de phase de vie adulte"),
            array("reference"=>"P27","description"=>"Peur d'un trouble mental"),
            array("reference"=>"P28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"P29","description"=>"Autre Symptôme/Plainte psychologique"),

            array("reference"=>"R01","description"=>"Douleur du syst. respiratoire"),
            array("reference"=>"R02","description"=>"Souffle court, dyspnée"),
            array("reference"=>"R03","description"=>"Sibilance"),
            array("reference"=>"R04","description"=>"Autre Problème respiratoire"),
            array("reference"=>"R05","description"=>"Toux"),
            array("reference"=>"R06","description"=>"Saignement de nez, épistaxis"),
            array("reference"=>"R07","description"=>"Congestion nasale, éternuement"),
            array("reference"=>"R08","description"=>"Autre Symptôme/Plainte du nez"),
            array("reference"=>"R09","description"=>"Symptôme/Plainte des sinus"),
            array("reference"=>"R21","description"=>"Symptôme/Plainte de la gorge"),
            array("reference"=>"R23","description"=>"Symptôme/Plainte de la voix"),
            array("reference"=>"R24","description"=>"Hémoptysie"),
            array("reference"=>"R25","description"=>"Expectoration/glaire anormale"),
            array("reference"=>"R26","description"=>"Peur d'un cancer du syst. respiratoire"),
            array("reference"=>"R27","description"=>"Peur d’une autre maladie respiratoire"),
            array("reference"=>"R28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"R29","description"=>"Autre Symptôme/Plainte respiratoire"),

            array("reference"=>"S01","description"=>"Douleur/hypersensibilité de la peau"),
            array("reference"=>"S02","description"=>"Prurit"),
            array("reference"=>"S03","description"=>"Verrue"),
            array("reference"=>"S04","description"=>"Tuméfaction/gonflement loc. peau"),
            array("reference"=>"S05","description"=>"Tuméfactions/gonflements gén. peau"),
            array("reference"=>"S06","description"=>"Eruption localisée"),
            array("reference"=>"S07","description"=>"Eruption généralisée"),
            array("reference"=>"S08","description"=>"Modification de la couleur de la peau"),
            array("reference"=>"S09","description"=>"Doigt/orteil infecté"),
            array("reference"=>"S10","description"=>"Furoncle/anthrax"),
            array("reference"=>"S11","description"=>"Infection post-traumat. de la peau"),
            array("reference"=>"S12","description"=>"Piqûre d'insecte"),
            array("reference"=>"S13","description"=>"Morsure animale/humaine"),
            array("reference"=>"S14","description"=>"Brûlure cutanée"),
            array("reference"=>"S15","description"=>"CE dans la peau"),
            array("reference"=>"S16","description"=>"Ecchymose/contusion"),
            array("reference"=>"S17","description"=>"Eraflure, égratignure, ampoule"),
            array("reference"=>"S18","description"=>"Coupure/lacération"),
            array("reference"=>"S19","description"=>"Autre lésion traumat. de la peau"),
            array("reference"=>"S20","description"=>"Cor/callosité"),
            array("reference"=>"S21","description"=>"Symptôme/Plainte au sujet de la texture de la peau"),
            array("reference"=>"S22","description"=>"Symptôme/Plainte de l'ongle"),
            array("reference"=>"S23","description"=>"Calvitie/perte de cheveux"),
            array("reference"=>"S24","description"=>"Autre Symptôme/Plainte cheveux, poils/cuir chevelu"),
            array("reference"=>"S26","description"=>"Peur du cancer de la peau"),
            array("reference"=>"S27","description"=>"Peur d’une autre maladie de la peau"),
            array("reference"=>"S28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"S29","description"=>"Autre Symptôme/Plainte de la peau"),

            array("reference"=>"T01","description"=>"Soif excessive"),
            array("reference"=>"T02","description"=>"Appétit excessif"),
            array("reference"=>"T03","description"=>"Perte d'appétit"),
            array("reference"=>"T04","description"=>"Problème d'alimentation nourrisson/enfant"),
            array("reference"=>"T05","description"=>"Problème d'alimentation de l'adulte"),
            array("reference"=>"T07","description"=>"Gain de poids"),
            array("reference"=>"T08","description"=>"Perte de poids"),
            array("reference"=>"T10","description"=>"Retard de croissance"),
            array("reference"=>"T11","description"=>"Déshydratation"),
            array("reference"=>"T26","description"=>"Peur d'un cancer du syst. endocrinien"),
            array("reference"=>"T27","description"=>"Peur autre mal. endoc/métab./nutrit."),
            array("reference"=>"T28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"T29","description"=>"Autre Symptôme/Plainte endoc/métab./nutrit."),

            array("reference"=>"U01","description"=>"Dysurie/miction douloureuse"),
            array("reference"=>"U02","description"=>"Miction fréquente/impérieuse"),
            array("reference"=>"U04","description"=>"Incontinence urinaire"),
            array("reference"=>"U05","description"=>"Autre Problème de miction"),
            array("reference"=>"U06","description"=>"Hématurie"),
            array("reference"=>"U07","description"=>"Autre Symptôme/Plainte au sujet de l'urine"),
            array("reference"=>"U08","description"=>"Rétention d'urine"),
            array("reference"=>"U13","description"=>"Autre Symptôme/Plainte de la vessie"),
            array("reference"=>"U14","description"=>"Symptôme/Plainte du rein"),
            array("reference"=>"U26","description"=>"Peur d'un cancer du syst. urinaire"),
            array("reference"=>"U27","description"=>"Peur d’une autre maladie urinaire"),
            array("reference"=>"U28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"U29","description"=>"Autre Symptôme/Plainte urinaire"),

            array("reference"=>"W01","description"=>"Question de grossesse"),
            array("reference"=>"W02","description"=>"Peur d'être enceinte"),
            array("reference"=>"W03","description"=>"Saignement pendant la grossesse"),
            array("reference"=>"W05","description"=>"Nausée/vomissement de grossesse"),
            array("reference"=>"W10","description"=>"Contraception post-coïtale"),
            array("reference"=>"W11","description"=>"Contraception orale"),
            array("reference"=>"W12","description"=>"Contraception intra-utérine"),
            array("reference"=>"W13","description"=>"Stérilisation chez la femme"),
            array("reference"=>"W14","description"=>"Autre contraception chez la femme"),
            array("reference"=>"W15","description"=>"Stérilité - hypofertilité de la femme"),
            array("reference"=>"W17","description"=>"Saignement du post-partum"),
            array("reference"=>"W18","description"=>"Autre Symptôme/Plainte du post-partum"),
            array("reference"=>"W19","description"=>"Symptôme/Plainte du sein/lactation post-partum"),
            array("reference"=>"W21","description"=>"Préoc. par modific. image et grossesse"),
            array("reference"=>"W27","description"=>"Peur complications de la grossesse"),
            array("reference"=>"W28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"W29","description"=>"Autre Symptôme/Plainte de la grossesse"),

            array("reference"=>"X01","description"=>"Douleur génitale chez la femme"),
            array("reference"=>"X02","description"=>"Douleur menstruelle"),
            array("reference"=>"X03","description"=>"Douleur intermenstruelle"),
            array("reference"=>"X04","description"=>"Rapport sexuel douloureux femme"),
            array("reference"=>"X05","description"=>"Menstruation absente/rare"),
            array("reference"=>"X06","description"=>"Menstruation excessive"),
            array("reference"=>"X07","description"=>"Menstruation irrégulière/fréquente"),
            array("reference"=>"X08","description"=>"Saignement intermenstruel"),
            array("reference"=>"X09","description"=>"Symptôme/Plainte prémenstruel"),
            array("reference"=>"X10","description"=>"Ajournement des menstruations"),
            array("reference"=>"X11","description"=>"Symptôme/Plainte liés a la ménopause"),
            array("reference"=>"X12","description"=>"Saignement de la post-ménopause"),
            array("reference"=>"X13","description"=>"Saignement post-coïtal femme"),
            array("reference"=>"X14","description"=>"Ecoulement vaginal"),
            array("reference"=>"X15","description"=>"Symptôme/Plainte du vagin"),
            array("reference"=>"X16","description"=>"Symptôme/Plainte de la vulve"),
            array("reference"=>"X17","description"=>"Symptôme/Plainte du petit bassin chez la femme"),
            array("reference"=>"X18","description"=>"Douleur du sein chez la femme"),
            array("reference"=>"X19","description"=>"Tuméfaction/masse du sein femme"),
            array("reference"=>"X20","description"=>"Symptôme/Plainte du mamelon chez la femme"),
            array("reference"=>"X21","description"=>"Autre Symptôme/Plainte du sein chez la femme"),
            array("reference"=>"X22","description"=>"Préoc. par l'apparence des seins"),
            array("reference"=>"X23","description"=>"Peur d'une MST chez la femme"),
            array("reference"=>"X24","description"=>"Peur dysfonction sexuelle femme"),
            array("reference"=>"X25","description"=>"Peur d'un cancer génital femme"),
            array("reference"=>"X26","description"=>"Peur d'un cancer du sein femme"),
            array("reference"=>"X27","description"=>"Peur autre mal. génitale/sein femme"),
            array("reference"=>"X28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"X29","description"=>"Autre Symptôme/Plainte génital chez la femme"),

            array("reference"=>"Y01","description"=>"Douleur du pénis"),
            array("reference"=>"Y02","description"=>"Douleur des testicules, du scrotum"),
            array("reference"=>"Y03","description"=>"Ecoulement urétral chez l'homme"),
            array("reference"=>"Y04","description"=>"Autre Symptôme/Plainte du pénis"),
            array("reference"=>"Y05","description"=>"Autre Symptôme/Plainte des testicules/du scrotum"),
            array("reference"=>"Y06","description"=>"Symptôme/Plainte de la prostate"),
            array("reference"=>"Y07","description"=>"Impuissance sexuelle"),
            array("reference"=>"Y08","description"=>"Autre Symptôme/Plainte fonction sexuelle homme"),
            array("reference"=>"Y10","description"=>"Stérilité, hypofertilité de l'homme"),
            array("reference"=>"Y13","description"=>"Stérilisation de l'homme"),
            array("reference"=>"Y14","description"=>"Autre PF chez l'homme"),
            array("reference"=>"Y16","description"=>"Symptôme/Plainte du sein chez l'homme"),
            array("reference"=>"Y24","description"=>"Peur dysfonction sexuelle homme"),
            array("reference"=>"Y25","description"=>"Peur d’une MST chez l'homme"),
            array("reference"=>"Y26","description"=>"Peur d'un cancer génital homme"),
            array("reference"=>"Y27","description"=>"Peur autre maladie génitale homme"),
            array("reference"=>"Y28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"Y29","description"=>"Autre Symptôme/Plainte génitale chez l'homme"),

            array("reference"=>"Z01","description"=>"Pauvreté/Problème économique"),
            array("reference"=>"Z02","description"=>"Problème d'eau/de nourriture"),
            array("reference"=>"Z03","description"=>"Problème d'habitat/de voisinage"),
            array("reference"=>"Z04","description"=>"Problème socioculturel"),
            array("reference"=>"Z05","description"=>"Problème de travail"),
            array("reference"=>"Z06","description"=>"Problème de non emploi"),
            array("reference"=>"Z07","description"=>"Problème d'éducation"),
            array("reference"=>"Z08","description"=>"Problème de protection sociale"),
            array("reference"=>"Z09","description"=>"Problème légal"),
            array("reference"=>"Z10","description"=>"Problème relatif au syst. de soins de santé"),
            array("reference"=>"Z11","description"=>"Problème du fait d'être malade/compliance"),
            array("reference"=>"Z12","description"=>"Problème de relation entre partenaires"),
            array("reference"=>"Z13","description"=>"Problème de comportement du partenaire"),
            array("reference"=>"Z14","description"=>"Problème du à la maladie du partenaire"),
            array("reference"=>"Z15","description"=>"Perte/décès du partenaire"),
            array("reference"=>"Z16","description"=>"Problème de relation avec un enfant"),
            array("reference"=>"Z18","description"=>"Problème du à la maladie d'un enfant"),
            array("reference"=>"Z19","description"=>"Perte/décès d'un enfant"),
            array("reference"=>"Z20","description"=>"Problème relation autre parent/famille"),
            array("reference"=>"Z21","description"=>"Problème comportem. autre parent/famille"),
            array("reference"=>"Z22","description"=>"Problème du à la mal. autre parent/famille"),
            array("reference"=>"Z23","description"=>"Perte/décès autre parent/famille"),
            array("reference"=>"Z24","description"=>"Problème de relation avec un ami"),
            array("reference"=>"Z25","description"=>"Agression/évènement nocif"),
            array("reference"=>"Z27","description"=>"Peur d'un Problème social"),
            array("reference"=>"Z28","description"=>"Limitation de la fonction/incap."),
            array("reference"=>"Z29","description"=>"Problème social"),
        );

        foreach ($motifs as $motif){
            \Illuminate\Support\Facades\DB::table('motifs')->insert([
                'reference'=>$motif['reference'],
                'description'=>$motif['description'],
                'slug'=>$motif['reference']
            ]);
        }
    }
}