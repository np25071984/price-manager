<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AliasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('aliases')->insert([
            't' => 'edt',
            's' => 'edt | туалетная & вода',
        ]);
        DB::table('aliases')->insert([
            't' => 'edp',
            's' => 'edp | парфюмированная & вода',
        ]);
        DB::table('aliases')->insert([
            't' => 'edc',
            's' => 'edc | одеколон | colonia',
        ]);
        DB::table('aliases')->insert([
            't' => 'tester',
            's' => 'tester | test | тест:*',
        ]);
        DB::table('aliases')->insert([
            't' => 'parfume',
            's' => 'parfume | parfum',
        ]);
        DB::table('aliases')->insert([
            't' => 'Parfumeurs',
            's' => 'parfumeurs | парфюмеров',
        ]);
        DB::table('aliases')->insert([
            't' => 'A.Banderas',
            's' => 'banderas | (antonio & banderas) | (антонио & бандерас) | а.бандерас | бандерас',
        ]);
        DB::table('aliases')->insert([
            't' => 'A.Dunhill',
            's' => 'dunhill | (alfred & dunhill) | (альфред & данхил) | а.данхил | данхил',
        ]);
        DB::table('aliases')->insert([
            't' => 'ABERCROMBIE',
            's' => 'abercrombie | аберкромбе',
        ]);
        DB::table('aliases')->insert([
            't' => 'FITCH',
            's' => 'fitch | фитчи',
        ]);
        DB::table('aliases')->insert([
            't' => 'Absolument',
            's' => 'absolument | абсолют',
        ]);
        DB::table('aliases')->insert([
            't' => 'Accendis',
            's' => 'accendis | акендис',
        ]);
        DB::table('aliases')->insert([
            't' => 'ACQUA',
            's' => 'acqua | аква',
        ]);
        DB::table('aliases')->insert([
            't' => 'PARMA',
            's' => 'parma | парма',
        ]);
        DB::table('aliases')->insert([
            't' => 'Adidas',
            's' => 'adidas | адидас',
        ]);
        DB::table('aliases')->insert([
            't' => 'Aedes',
            's' => 'aedes | аедес | аедис',
        ]);
        DB::table('aliases')->insert([
            't' => 'Venustas',
            's' => 'venustas | венустас',
        ]);
        DB::table('aliases')->insert([
            't' => 'AERIN',
            's' => 'aerin | аерин',
        ]);
        DB::table('aliases')->insert([
            't' => 'AETHER',
            's' => 'aether | аетер',
        ]);
        DB::table('aliases')->insert([
            't' => 'Affinessence',
            's' => 'affinessence | аффинессенс',
        ]);
        DB::table('aliases')->insert([
            't' => 'AFNAN',
            's' => 'afnan | афнан',
        ]);
        DB::table('aliases')->insert([
            't' => 'Agent',
            's' => 'agent | агент',
        ]);
        DB::table('aliases')->insert([
            't' => 'Provocateur',
            's' => 'provocateur | провокатор',
        ]);
        DB::table('aliases')->insert([
            't' => 'Agonist',
            's' => 'agonist | агонист',
        ]);
        DB::table('aliases')->insert([
            't' => 'Aigner',
            's' => 'aigner | эйгнер',
        ]);
        DB::table('aliases')->insert([
            't' => 'AJMAL',
            's' => 'ajmal | аджамал | ажамал',
        ]);
        DB::table('aliases')->insert([
            't' => 'ALEX ',
            's' => 'alex | алекс',
        ]);
        DB::table('aliases')->insert([
            't' => 'SIMONE',
            's' => 'simone | саймон',
        ]);
        DB::table('aliases')->insert([
            't' => 'Alexandre',
            's' => 'alexandre | александр',
        ]);
        DB::table('aliases')->insert([
            't' => 'AMOUAGE',
            's' => 'amouage | амуаж',
        ]);
        DB::table('aliases')->insert([
            't' => 'Angel',
            's' => 'angel | ангел',
        ]);
        DB::table('aliases')->insert([
            't' => 'Schlesser',
            's' => 'schlesser | счлессер',
        ]);
        DB::table('aliases')->insert([
            't' => 'Annick',
            's' => 'annick | анник',
        ]);
        DB::table('aliases')->insert([
            't' => 'Goutal',
            's' => 'goutal | гоутал',
        ]);
        DB::table('aliases')->insert([
            't' => 'Armand',
            's' => 'armand | арманд',
        ]);
        DB::table('aliases')->insert([
            't' => 'Basi',
            's' => 'basi | a.basi | баси',
        ]);
        DB::table('aliases')->insert([
            't' => 'Atelier',
            's' => 'atelier | ательер',
        ]);
        DB::table('aliases')->insert([
            't' => 'Cologne',
            's' => 'cologne | колон',
        ]);
        DB::table('aliases')->insert([
            't' => 'Flou',
            's' => 'flou | флоу',
        ]);
        DB::table('aliases')->insert([
            't' => 'Ors',
            's' => 'ors | орс',
        ]);
        DB::table('aliases')->insert([
            't' => 'ATKINSONS',
            's' => 'atkinsons | аткинсон',
        ]);
        DB::table('aliases')->insert([
            't' => 'ATTAR',
            's' => 'attar | аттар',
        ]);
        DB::table('aliases')->insert([
            't' => 'AZZARO',
            's' => 'azzaro | аззаро',
        ]);
        DB::table('aliases')->insert([
            't' => 'BALDI',
            's' => 'baldi | балди',
        ]);
        DB::table('aliases')->insert([
            't' => 'Balenciaga',
            's' => 'balenciaga | баленсиага',
        ]);
        DB::table('aliases')->insert([
            't' => 'Bois',
            's' => 'bois | боис',
        ]);
        DB::table('aliases')->insert([
            't' => 'Borlind',
            's' => 'borlind | a.borlind | берлинг',
        ]);
        DB::table('aliases')->insert([
            't' => 'Boss',
            's' => 'boss | босс',
        ]);
        DB::table('aliases')->insert([
            't' => 'Baldessarini',
            's' => 'baldessarini | балдессарини',
        ]);
        DB::table('aliases')->insert([
            't' => 'Burberry',
            's' => 'burberry | бербери',
        ]);
        DB::table('aliases')->insert([
            't' => 'Bvlgari',
            's' => 'bvlgari | булгари',
        ]);
        DB::table('aliases')->insert([
            't' => 'BYREDO',
            's' => 'byredo | байредо',
        ]);
        DB::table('aliases')->insert([
            't' => 'Dior',
            's' => 'dior | c.dior | кристиан | диор | к.диор',
        ]);
        DB::table('aliases')->insert([
            't' => 'Cacharel',
            's' => 'cacharel | кашарель',
        ]);
        DB::table('aliases')->insert([
            't' => 'Calvin',
            's' => 'calvin | ck | ск | кельвин',
        ]);
        DB::table('aliases')->insert([
            't' => 'Klein',
            's' => 'klein | кляйн',
        ]);
        DB::table('aliases')->insert([
            't' => 'Carolina',
            's' => 'carolina | ch | каролина',
        ]);
        DB::table('aliases')->insert([
            't' => 'Herrera',
            's' => 'herrera | херерра',
        ]);
        DB::table('aliases')->insert([
            't' => 'Cartier',
            's' => 'cartier | картьер',
        ]);
        DB::table('aliases')->insert([
            't' => 'Chabaud',
            's' => 'chabaud | шабауд',
        ]);
        DB::table('aliases')->insert([
            't' => 'CHANEL',
            's' => 'chanel | шанель',
        ]);
        DB::table('aliases')->insert([
            't' => 'CHLOE',
            's' => 'chloe | хлоя',
        ]);
        DB::table('aliases')->insert([
            't' => 'Chopard',
            's' => 'chopard | шопард',
        ]);
        DB::table('aliases')->insert([
            't' => 'Christian',
            's' => 'christian | кристиан',
        ]);
        DB::table('aliases')->insert([
            't' => 'Lacroix',
            's' => 'lacroix | c.lacroix | лакруз',
        ]);
        DB::table('aliases')->insert([
            't' => 'Clinique',
            's' => 'clinique | клиник',
        ]);
        DB::table('aliases')->insert([
            't' => 'Costume',
            's' => 'costume | костюм',
        ]);
        DB::table('aliases')->insert([
            't' => 'National',
            's' => 'national | националь',
        ]);
        DB::table('aliases')->insert([
            't' => 'CREED',
            's' => 'creed | крид',
        ]);
        DB::table('aliases')->insert([
            't' => 'Davidoff',
            's' => 'davidoff | давидофф',
        ]);
        DB::table('aliases')->insert([
            't' => 'Dolce',
            's' => 'dolce | dg | дольче',
        ]);
        DB::table('aliases')->insert([
            't' => 'Donna',
            's' => 'donna | донна',
        ]);
        DB::table('aliases')->insert([
            't' => 'Karan',
            's' => 'karan | dkny | d.karan | каран',
        ]);
        DB::table('aliases')->insert([
            't' => 'Escada',
            's' => 'escada | эскада | ескада',
        ]);
        DB::table('aliases')->insert([
            't' => 'Escentric',
            's' => 'escentric | эксцентрик',
        ]);
        DB::table('aliases')->insert([
            't' => 'Molecule',
            's' => 'molecule | молекула',
        ]);
        DB::table('aliases')->insert([
            't' => 'ESTEE',
            's' => 'estee | эсте',
        ]);
        DB::table('aliases')->insert([
            't' => 'LAUDER',
            's' => 'lauder | лаудер',
        ]);
        DB::table('aliases')->insert([
            't' => 'NIHILO',
            's' => 'nihilo | нихило',
        ]);
        DB::table('aliases')->insert([
            't' => 'FENDI',
            's' => 'fendi | фенди',
        ]);
        DB::table('aliases')->insert([
            't' => 'Fragonard',
            's' => 'fragonard | фрагонард',
        ]);
        DB::table('aliases')->insert([
            't' => 'Frapin',
            's' => 'frapin | фрапин',
        ]);
        DB::table('aliases')->insert([
            't' => 'Giorgio',
            's' => 'giorgio | джорджио',
        ]);
        DB::table('aliases')->insert([
            't' => 'Armani',
            's' => 'armani | g.armani | армани',
        ]);
        DB::table('aliases')->insert([
            't' => 'Givenchy',
            's' => 'givenchy | дживанши',
        ]);
        DB::table('aliases')->insert([
            't' => 'GMV',
            's' => 'gmv | gian | marco | venturi | gianmarco',
        ]);
        DB::table('aliases')->insert([
            't' => 'Gucci',
            's' => 'gucci | гучи',
        ]);
        DB::table('aliases')->insert([
            't' => 'Guerlain',
            's' => 'guerlain | герлен',
        ]);
        DB::table('aliases')->insert([
            't' => 'Hermes',
            's' => 'hermes | гермес',
        ]);
        DB::table('aliases')->insert([
            't' => 'Hugo',
            's' => 'hugo | хюго',
        ]);
        DB::table('aliases')->insert([
            't' => 'JIMMY',
            's' => 'jimmy | джими',
        ]);
        DB::table('aliases')->insert([
            't' => 'CHOO',
            's' => 'choo | чу',
        ]);
        DB::table('aliases')->insert([
            't' => 'Malone',
            's' => 'malone | малоне',
        ]);
        DB::table('aliases')->insert([
            't' => 'Juliette',
            's' => 'Juliette | джульета',
        ]);
        DB::table('aliases')->insert([
            't' => 'gun',
            's' => 'gun | пистолет',
        ]);
        DB::table('aliases')->insert([
            't' => 'Mecheri',
            's' => 'mecheri | мечери',
        ]);
        DB::table('aliases')->insert([
            't' => 'Kenzo',
            's' => 'kenzo | кензо',
        ]);
        DB::table('aliases')->insert([
            't' => 'Kilian',
            's' => 'kilian | килиан',
        ]);
        DB::table('aliases')->insert([
            't' => 'Lacoste',
            's' => 'lacoste | лакост',
        ]);
        DB::table('aliases')->insert([
            't' => 'Lalique',
            's' => 'lalique | лалик',
        ]);
        DB::table('aliases')->insert([
            't' => 'LANCOME',
            's' => 'lancome | ланком',
        ]);
        DB::table('aliases')->insert([
            't' => 'Lanvin',
            's' => 'lanvin | ланвин',
        ]);
        DB::table('aliases')->insert([
            't' => 'Linari',
            's' => 'Linari | Линари',
        ]);
        DB::table('aliases')->insert([
            't' => 'INT',
            's' => 'int | mint | минт',
        ]);
        DB::table('aliases')->insert([
            't' => 'Micallef',
            's' => 'micallef | микаллеф',
        ]);
        DB::table('aliases')->insert([
            't' => 'Kurkdjian',
            's' => 'kurkdjian | куркиджан',
        ]);
        DB::table('aliases')->insert([
            't' => 'Montale',
            's' => 'montale | монталь',
        ]);
        DB::table('aliases')->insert([
            't' => 'Moschino',
            's' => 'moschino | маскино',
        ]);
        DB::table('aliases')->insert([
            't' => 'Mugler',
            's' => 'mugler | муглер',
        ]);
        DB::table('aliases')->insert([
            't' => 'Narciso',
            's' => 'narciso | нарцис',
        ]);
        DB::table('aliases')->insert([
            't' => 'Rodriguez',
            's' => 'rodriguez | родригез',
        ]);
        DB::table('aliases')->insert([
            't' => 'Nina',
            's' => 'nina | нина',
        ]);
        DB::table('aliases')->insert([
            't' => 'Ricci',
            's' => 'ricci | n.ricci | ричи',
        ]);
        DB::table('aliases')->insert([
            't' => 'Nobile',
            's' => 'nobile | нобиль',
        ]);
        DB::table('aliases')->insert([
            't' => 'OLIBERE',
            's' => 'olibere | олибери',
        ]);
        DB::table('aliases')->insert([
            't' => 'ONYRICO',
            's' => 'onyrico | онирико',
        ]);
        DB::table('aliases')->insert([
            't' => 'Paco',
            's' => 'paco | пако',
        ]);
        DB::table('aliases')->insert([
            't' => 'Rabanne',
            's' => 'rabanne | p.rabanne | рабане',
        ]);
        DB::table('aliases')->insert([
            't' => 'Paolo',
            's' => 'paolo | паоло',
        ]);
        DB::table('aliases')->insert([
            't' => 'Gigli',
            's' => 'gigli | гигли',
        ]);
        DB::table('aliases')->insert([
            't' => 'Marly',
            's' => 'marly | марли',
        ]);
        DB::table('aliases')->insert([
            't' => 'Piguet',
            's' => 'piguet | пиге',
        ]);
        DB::table('aliases')->insert([
            't' => 'Cosac',
            's' => 'cosac | s.cosac | косак',
        ]);
        DB::table('aliases')->insert([
            't' => 'Shaik',
            's' => 'shaik | шейк',
        ]);
        DB::table('aliases')->insert([
            't' => 'SIMIMI',
            's' => 'simimi | симими',
        ]);
        DB::table('aliases')->insert([
            't' => 'Sisley',
            's' => 'sisley | сисли',
        ]);
        DB::table('aliases')->insert([
            't' => 'TIFFANY',
            's' => 'tiffany | тиффани',
        ]);
        DB::table('aliases')->insert([
            't' => 'Tom',
            's' => 'tom | том',
        ]);
        DB::table('aliases')->insert([
            't' => 'Ford',
            's' => 'ford | форд',
        ]);
        DB::table('aliases')->insert([
            't' => 'Trussardi',
            's' => 'trussardi | трусарди',
        ]);
        DB::table('aliases')->insert([
            't' => 'Versace',
            's' => 'versace | версаче',
        ]);
        DB::table('aliases')->insert([
            't' => 'Victoria',
            's' => 'victoria | виктория',
        ]);
        DB::table('aliases')->insert([
            't' => 'Secret',
            's' => 'secret | сикрет',
        ]);
        DB::table('aliases')->insert([
            't' => 'XERJOFF',
            's' => 'XERJOFF | Casamorati | Ксержофф',
        ]);
        DB::table('aliases')->insert([
            't' => 'YSL',
            's' => 'YSL | Yves | Saint | Laurent | лоран',
        ]);
    }
}
