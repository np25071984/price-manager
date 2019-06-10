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
            't' => 'test',
            's' => 'test:* | тест:*',
        ]);
        DB::table('aliases')->insert([
            't' => 'parfume',
            's' => 'parfume | parfum',
        ]);
        DB::table('aliases')->insert([
            't' => 'parfumeurs',
            's' => 'parfumeurs | парфюмеров',
        ]);
        DB::table('aliases')->insert([
            't' => 'a.banderas',
            's' => 'banderas | (antonio & banderas) | (антонио & бандерас) | а.бандерас | бандерас',
        ]);
        DB::table('aliases')->insert([
            't' => 'a.dunhill',
            's' => 'dunhill | (alfred & dunhill) | (альфред & данхил) | а.данхил | данхил',
        ]);
        DB::table('aliases')->insert([
            't' => 'abercrombie',
            's' => 'abercrombie | аберкромбе',
        ]);
        DB::table('aliases')->insert([
            't' => 'fitch',
            's' => 'fitch | фитчи',
        ]);
        DB::table('aliases')->insert([
            't' => 'absolument',
            's' => 'absolument | абсолют',
        ]);
        DB::table('aliases')->insert([
            't' => 'accendis',
            's' => 'accendis | акендис',
        ]);
        DB::table('aliases')->insert([
            't' => 'acqua',
            's' => 'acqua | аква',
        ]);
        DB::table('aliases')->insert([
            't' => 'parma',
            's' => 'parma | парма',
        ]);
        DB::table('aliases')->insert([
            't' => 'adidas',
            's' => 'adidas | адидас',
        ]);
        DB::table('aliases')->insert([
            't' => 'aedes',
            's' => 'aedes | аедес | аедис',
        ]);
        DB::table('aliases')->insert([
            't' => 'venustas',
            's' => 'venustas | венустас',
        ]);
        DB::table('aliases')->insert([
            't' => 'aerin',
            's' => 'aerin | аерин',
        ]);
        DB::table('aliases')->insert([
            't' => 'aether',
            's' => 'aether | аетер',
        ]);
        DB::table('aliases')->insert([
            't' => 'affinessence',
            's' => 'affinessence | аффинессенс',
        ]);
        DB::table('aliases')->insert([
            't' => 'afnan',
            's' => 'afnan | афнан',
        ]);
        DB::table('aliases')->insert([
            't' => 'agent',
            's' => 'agent | агент',
        ]);
        DB::table('aliases')->insert([
            't' => 'provocateur',
            's' => 'provocateur | провокатор',
        ]);
        DB::table('aliases')->insert([
            't' => 'agonist',
            's' => 'agonist | агонист',
        ]);
        DB::table('aliases')->insert([
            't' => 'aigner',
            's' => 'aigner | эйгнер',
        ]);
        DB::table('aliases')->insert([
            't' => 'ajmal',
            's' => 'ajmal | аджамал | ажамал',
        ]);
        DB::table('aliases')->insert([
            't' => 'alex ',
            's' => 'alex | алекс',
        ]);
        DB::table('aliases')->insert([
            't' => 'simone',
            's' => 'simone | саймон',
        ]);
        DB::table('aliases')->insert([
            't' => 'alexandre',
            's' => 'alexandre | александр',
        ]);
        DB::table('aliases')->insert([
            't' => 'amouage',
            's' => 'amouage | амуаж',
        ]);
        DB::table('aliases')->insert([
            't' => 'angel',
            's' => 'angel | ангел',
        ]);
        DB::table('aliases')->insert([
            't' => 'schlesser',
            's' => 'schlesser | счлессер',
        ]);
        DB::table('aliases')->insert([
            't' => 'annick',
            's' => 'annick | анник',
        ]);
        DB::table('aliases')->insert([
            't' => 'goutal',
            's' => 'goutal | гоутал',
        ]);
        DB::table('aliases')->insert([
            't' => 'armand',
            's' => 'armand | арманд',
        ]);
        DB::table('aliases')->insert([
            't' => 'basi',
            's' => 'basi | a.basi | баси',
        ]);
        DB::table('aliases')->insert([
            't' => 'atelier',
            's' => 'atelier | ательер',
        ]);
        DB::table('aliases')->insert([
            't' => 'cologne',
            's' => 'cologne | колон',
        ]);
        DB::table('aliases')->insert([
            't' => 'flou',
            's' => 'flou | флоу',
        ]);
        DB::table('aliases')->insert([
            't' => 'ors',
            's' => 'ors | орс',
        ]);
        DB::table('aliases')->insert([
            't' => 'atkinsons',
            's' => 'atkinsons | аткинсон',
        ]);
        DB::table('aliases')->insert([
            't' => 'attar',
            's' => 'attar | аттар',
        ]);
        DB::table('aliases')->insert([
            't' => 'azzaro',
            's' => 'azzaro | аззаро',
        ]);
        DB::table('aliases')->insert([
            't' => 'baldi',
            's' => 'baldi | балди',
        ]);
        DB::table('aliases')->insert([
            't' => 'balenciaga',
            's' => 'balenciaga | баленсиага',
        ]);
        DB::table('aliases')->insert([
            't' => 'bois',
            's' => 'bois | боис',
        ]);
        DB::table('aliases')->insert([
            't' => 'borlind',
            's' => 'borlind | a.borlind | берлинг',
        ]);
        DB::table('aliases')->insert([
            't' => 'boss',
            's' => 'boss | босс',
        ]);
        DB::table('aliases')->insert([
            't' => 'baldessarini',
            's' => 'baldessarini | балдессарини',
        ]);
        DB::table('aliases')->insert([
            't' => 'burberry',
            's' => 'burberry | бербери',
        ]);
        DB::table('aliases')->insert([
            't' => 'bvlgari',
            's' => 'bvlgari | булгари',
        ]);
        DB::table('aliases')->insert([
            't' => 'byredo',
            's' => 'byredo | байредо',
        ]);
        DB::table('aliases')->insert([
            't' => 'dior',
            's' => 'dior | c.dior | кристиан | диор | к.диор',
        ]);
        DB::table('aliases')->insert([
            't' => 'cacharel',
            's' => 'cacharel | кашарель',
        ]);
        DB::table('aliases')->insert([
            't' => 'calvin',
            's' => 'calvin | ck | ск | кельвин',
        ]);
        DB::table('aliases')->insert([
            't' => 'klein',
            's' => 'klein | кляйн',
        ]);
        DB::table('aliases')->insert([
            't' => 'carolina',
            's' => 'carolina | ch | каролина',
        ]);
        DB::table('aliases')->insert([
            't' => 'herrera',
            's' => 'herrera | херерра',
        ]);
        DB::table('aliases')->insert([
            't' => 'cartier',
            's' => 'cartier | картьер',
        ]);
        DB::table('aliases')->insert([
            't' => 'chabaud',
            's' => 'chabaud | шабауд',
        ]);
        DB::table('aliases')->insert([
            't' => 'chanel',
            's' => 'chanel | шанель',
        ]);
        DB::table('aliases')->insert([
            't' => 'chloe',
            's' => 'chloe | хлоя',
        ]);
        DB::table('aliases')->insert([
            't' => 'chopard',
            's' => 'chopard | шопард',
        ]);
        DB::table('aliases')->insert([
            't' => 'christian',
            's' => 'christian | кристиан',
        ]);
        DB::table('aliases')->insert([
            't' => 'lacroix',
            's' => 'lacroix | c.lacroix | лакруз',
        ]);
        DB::table('aliases')->insert([
            't' => 'clinique',
            's' => 'clinique | клиник',
        ]);
        DB::table('aliases')->insert([
            't' => 'costume',
            's' => 'costume | костюм',
        ]);
        DB::table('aliases')->insert([
            't' => 'national',
            's' => 'national | националь',
        ]);
        DB::table('aliases')->insert([
            't' => 'creed',
            's' => 'creed | крид',
        ]);
        DB::table('aliases')->insert([
            't' => 'davidoff',
            's' => 'davidoff | давидофф',
        ]);
        DB::table('aliases')->insert([
            't' => 'dolce',
            's' => 'dolce | dg | дольче',
        ]);
        DB::table('aliases')->insert([
            't' => 'donna',
            's' => 'donna | донна',
        ]);
        DB::table('aliases')->insert([
            't' => 'karan',
            's' => 'karan | dkny | d.karan | каран',
        ]);
        DB::table('aliases')->insert([
            't' => 'escada',
            's' => 'escada | эскада | ескада',
        ]);
        DB::table('aliases')->insert([
            't' => 'escentric',
            's' => 'escentric | эксцентрик',
        ]);
        DB::table('aliases')->insert([
            't' => 'molecule',
            's' => 'molecule | молекула',
        ]);
        DB::table('aliases')->insert([
            't' => 'estee',
            's' => 'estee | эсте',
        ]);
        DB::table('aliases')->insert([
            't' => 'lauder',
            's' => 'lauder | лаудер',
        ]);
        DB::table('aliases')->insert([
            't' => 'nihilo',
            's' => 'nihilo | нихило',
        ]);
        DB::table('aliases')->insert([
            't' => 'fendi',
            's' => 'fendi | фенди',
        ]);
        DB::table('aliases')->insert([
            't' => 'fragonard',
            's' => 'fragonard | фрагонард',
        ]);
        DB::table('aliases')->insert([
            't' => 'frapin',
            's' => 'frapin | фрапин',
        ]);
        DB::table('aliases')->insert([
            't' => 'giorgio',
            's' => 'giorgio | джорджио',
        ]);
        DB::table('aliases')->insert([
            't' => 'armani',
            's' => 'armani | g.armani | армани',
        ]);
        DB::table('aliases')->insert([
            't' => 'givenchy',
            's' => 'givenchy | дживанши',
        ]);
        DB::table('aliases')->insert([
            't' => 'gmv',
            's' => 'gmv | gian | marco | venturi | gianmarco',
        ]);
        DB::table('aliases')->insert([
            't' => 'gucci',
            's' => 'gucci | гучи',
        ]);
        DB::table('aliases')->insert([
            't' => 'guerlain',
            's' => 'guerlain | герлен',
        ]);
        DB::table('aliases')->insert([
            't' => 'hermes',
            's' => 'hermes | гермес',
        ]);
        DB::table('aliases')->insert([
            't' => 'hugo',
            's' => 'hugo | хюго',
        ]);
        DB::table('aliases')->insert([
            't' => 'jimmy',
            's' => 'jimmy | джими',
        ]);
        DB::table('aliases')->insert([
            't' => 'choo',
            's' => 'choo | чу',
        ]);
        DB::table('aliases')->insert([
            't' => 'malone',
            's' => 'malone | малоне',
        ]);
        DB::table('aliases')->insert([
            't' => 'juliette',
            's' => 'juliette | джульета',
        ]);
        DB::table('aliases')->insert([
            't' => 'gun',
            's' => 'gun | пистолет',
        ]);
        DB::table('aliases')->insert([
            't' => 'mecheri',
            's' => 'mecheri | мечери',
        ]);
        DB::table('aliases')->insert([
            't' => 'kenzo',
            's' => 'kenzo | кензо',
        ]);
        DB::table('aliases')->insert([
            't' => 'kilian',
            's' => 'kilian | килиан',
        ]);
        DB::table('aliases')->insert([
            't' => 'lacoste',
            's' => 'lacoste | лакост',
        ]);
        DB::table('aliases')->insert([
            't' => 'lalique',
            's' => 'lalique | лалик',
        ]);
        DB::table('aliases')->insert([
            't' => 'lancome',
            's' => 'lancome | ланком',
        ]);
        DB::table('aliases')->insert([
            't' => 'lanvin',
            's' => 'lanvin | ланвин',
        ]);
        DB::table('aliases')->insert([
            't' => 'linari',
            's' => 'linari | линари',
        ]);
        DB::table('aliases')->insert([
            't' => 'int',
            's' => 'int | mint | минт',
        ]);
        DB::table('aliases')->insert([
            't' => 'micallef',
            's' => 'micallef | микаллеф',
        ]);
        DB::table('aliases')->insert([
            't' => 'kurkdjian',
            's' => 'kurkdjian | куркиджан',
        ]);
        DB::table('aliases')->insert([
            't' => 'montale',
            's' => 'montale | монталь',
        ]);
        DB::table('aliases')->insert([
            't' => 'moschino',
            's' => 'moschino | маскино',
        ]);
        DB::table('aliases')->insert([
            't' => 'mugler',
            's' => 'mugler | муглер',
        ]);
        DB::table('aliases')->insert([
            't' => 'narciso',
            's' => 'narciso | нарцис',
        ]);
        DB::table('aliases')->insert([
            't' => 'rodriguez',
            's' => 'rodriguez | родригез',
        ]);
        DB::table('aliases')->insert([
            't' => 'nina',
            's' => 'nina | нина',
        ]);
        DB::table('aliases')->insert([
            't' => 'ricci',
            's' => 'ricci | n.ricci | ричи',
        ]);
        DB::table('aliases')->insert([
            't' => 'nobile',
            's' => 'nobile | нобиль',
        ]);
        DB::table('aliases')->insert([
            't' => 'olibere',
            's' => 'olibere | олибери',
        ]);
        DB::table('aliases')->insert([
            't' => 'onyrico',
            's' => 'onyrico | онирико',
        ]);
        DB::table('aliases')->insert([
            't' => 'paco',
            's' => 'paco | пако',
        ]);
        DB::table('aliases')->insert([
            't' => 'rabanne',
            's' => 'rabanne | p.rabanne | рабане',
        ]);
        DB::table('aliases')->insert([
            't' => 'paolo',
            's' => 'paolo | паоло',
        ]);
        DB::table('aliases')->insert([
            't' => 'gigli',
            's' => 'gigli | гигли',
        ]);
        DB::table('aliases')->insert([
            't' => 'marly',
            's' => 'marly | марли',
        ]);
        DB::table('aliases')->insert([
            't' => 'piguet',
            's' => 'piguet | пиге',
        ]);
        DB::table('aliases')->insert([
            't' => 'cosac',
            's' => 'cosac | s.cosac | косак',
        ]);
        DB::table('aliases')->insert([
            't' => 'shaik',
            's' => 'shaik | шейк',
        ]);
        DB::table('aliases')->insert([
            't' => 'simimi',
            's' => 'simimi | симими',
        ]);
        DB::table('aliases')->insert([
            't' => 'sisley',
            's' => 'sisley | сисли',
        ]);
        DB::table('aliases')->insert([
            't' => 'tiffany',
            's' => 'tiffany | тиффани',
        ]);
        DB::table('aliases')->insert([
            't' => 'tom',
            's' => 'tom | том',
        ]);
        DB::table('aliases')->insert([
            't' => 'ford',
            's' => 'ford | форд',
        ]);
        DB::table('aliases')->insert([
            't' => 'trussardi',
            's' => 'trussardi | трусарди',
        ]);
        DB::table('aliases')->insert([
            't' => 'versace',
            's' => 'versace | версаче',
        ]);
        DB::table('aliases')->insert([
            't' => 'victoria',
            's' => 'victoria | виктория',
        ]);
        DB::table('aliases')->insert([
            't' => 'secret',
            's' => 'secret | сикрет',
        ]);
        DB::table('aliases')->insert([
            't' => 'xerjoff',
            's' => 'xerjoff | casamorati | ксержофф',
        ]);
        DB::table('aliases')->insert([
            't' => 'ysl',
            's' => 'ysl | (Yves & Saint & Laurent) | (ив & сен & лоран)',
        ]);
    }
}
