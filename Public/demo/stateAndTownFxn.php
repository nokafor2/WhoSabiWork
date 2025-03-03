<?php

function displayTownOptions($state) {

	$statesAndTowns = array(
		"lagos" => array("Ajeromi", "Trade Fair", "Amuwo Odofin", "Badagry", "Ejinrin", "Erodo", "Agbowa", "Ijebu", "Lekki", "Ikorodu Rural", "Irepodun", "Ojo", "Ajangbadi Afromedia", "Okokomaiko", "Igbo Elerin", "Ajangbadi Ikemba House", "Ilemba Awori", "Igbede", "Ilogbo", "Shibiri Ekune", "Iba Town New Site", "Olojo", "Ira", "Alaba", "Maryland", "Alausa", "Ogba Aguda", "Ojodu", "Isheri Oke", "Ifako Agege", "Iju Water Works", "Iju Isaga", "Oworosoki", "Oworosoki L and K", "Abule", "Shomolu Central", "Anthony", "Shomolu Pedro", "Gbagada", "Atunrase Estate Agbagada", "Ojota", "Ketu", "Alapere Ketu", "Ketu Orisigun", "Ikosi", "Ketu Mile 12", "Magodo", "Oremeji Ifako", "Onipanu", "Mushin", "Lawanson", "Oshodi", "Isolo", "Ilasamaja", "Ejigbo Orile Owo", "Ikotun", "Ijegun", "Igando", "Egan", "Obadore", "Idimu", "Ikeja", "Murtala Muhammed Airport", "Dopemu", "Oya Estate Police Barracks", "Alimosho", "Abule Egba", "Ipaja", "Allen", "Ikeja Oba Akran", "Agege", "Oko Oba Agege", "Olota", "Akintan", "Jankara", "Ojokoro", "Alagbado", "Ahmadiya", "Suberu Oje", "Meiran", "Alakuko", "Ijare", "Agbelekale", "Aboru", "Oke Odo", "Ebute Meta West", "Yaba/Ebute Meta East", "Onike", "Balogun", "Dolphin Estate", "Lafiaji", "Itafaji", "Isale Eko", "Oke Arin", "Epe Tedo", "Obalende", "Agarawu", "Idumagbo", "Onikan", "Ikoyi", "Victoria Island", "1004 Area", "Maroko Extension", "Ajah", "Apapa Central", "Apapa North", "Apapa South", "Apapa West", "Ijora Oloye", "Orile Igamu", "Surulere Central", "Ajegunle Boundary", "Amukoko Alaba Oro", "Amukoko West", "Amukoko East", "Amukoko North", "Bakare Faro", "Ijora Badia East", "Ijora Badia Central", "Ijora Badai West", "Ijora Badia North", " CI Sari Iganmu Orile South", "Sari Iganmu Orile North", "Olodi Apapa", "FESTAC Community 1", "FESTAC Community 2", "FESTAC Community 3", "FESTAC Community 4", "Olute", "Satellite Town", "Alakija", "Site C", "Ilaje", "Kirikiri Phase 3", "Kirikiri Area", "Kirikiri Industrial Area", "Ajara Agamathem Vetho", "Ibereko", "Asago Sango", "Hospital Road Ascon", "Seme Badagry Iyafin", "Oba", "Isele 1", "Lowa Estate", "Ogolonto", "Owutu", "Abule Agbon", "Ori Okuta", "Isawo", "Dagbolu", "Eyita", "Lambo Lasunwom village", "Federal Lowcost Housing Estate", "Jala Dugbo Area", "Kokoroabu", "Sabo", "Isele", "Anibaba", "Aleje", "Gbasemo", "Solebo Area", "Aga", "Ipakodo"),
		
		"ogun" => array("Abeokuta", "Totoro", "Alabata Area", "Odeda", "Osiele Abeokuta", "Ayetoro", "Imeko", "Ibara Area", "Owode", "Owode-Yewa", "Ilaro", "Oke-Odan", "Ijebu Ode", "Odobolu", "Ijebu Mushin", "Isonyin", "Abigi", "Ogbere", "Ayila", "Aiyepe", "Odogbolu", "Ijebu Igbo", "Ago Iwoye", "Oru", "Sagamu", "Iperu", "Ikenne", "Isara", "Oderemo", "Ilisan", "Obafemi Owode", "Imeko", "Abeokuta", "Ogunmakin", "Igbore", "Etega", "Opeji", "Itoko Abeokuta", "Alamala Abk", "Oba", "Alapako", "Iboro", "Imasai", "Ibeshe", "Igan Alade", "Oja Odan", "Igbogila", "Ajilete", "Ipokia", "Idiroko", "Joga Orile", "Obalende", "Itele", "Iperin", "Iwopin", "Ibiade", "Imodi-Imosan", "Ogbogbo", "Ososa", "Itese", "New Road", "Awa", "Ilishan", "Ijokun", "Emuren", "Ilara", "Ilisan", "Sagamu", "Makun", "Ogere", "Ogijo", "Ajegunle", "Ejino", "Sagamu", "Irolu Remo", "Ipara"),
		
		"oyo" => array("Dugbe", "Oja Oba", "Eleyele", "Oyo", "Isokun", "Awe", "Iseyin", "Igbojaye", "Okeho", "Saki", "Tade", "Sepeteri", "Ago Are", "Ago Amodu", "Takie", "Arowomole", "Ajaawa", "Ikoyi Ile", "Kishi", "Igbeti", "Oba Ago", "Igbo Ora", "Tapa", "Ibadan", "Ilora", "Agunpopo", "Fiditi", "Iseyin", "Adoawaye", "Ayetoro-oke", "Apete", "Ijaiye", "Ikoloba", "Total Garden", "Ilua", "Isemi-Ile", "Okaka", "Ipapo", "Otu", "Komu", "Iganna", "Idiko-Ile", "Iwere-ile", "Ijio"),
		
		"osun" => array("Osogbo", "Ede", "Ifon Osun", "Iwo", "Ejigbo", "Ile-Ogbo", "Olla", "Kuta", "Iragbiji", "Iresi", "Igbajo", "Ada", "Ikirun", "Iba", "Okuku", "Oyan", "Igbaye", "Ilobu", "Erin-Osin", "Iree", "Otan-Ayegbaju", "Ila", "Oke-Ija", "Ora", "Inisa", "Ekosin", "Ile-Ife", "Garrage", "Olode", "Ifetedo", "OAU", "Moro", "Edunabon", "Ipetumodu", "Modakeke", "Gbongan", "Ode-Omu", "Orile-Owu", "Ikire", "Apomu", "Ilesa", "Iwara", "Osu", "Ifewara", "Ibokun", "Imesi-Ile", "Esa-Oke", "Ijebu-Jesa", "Iloko", "Erinmo", "Eti-Oni", "Igangan", "Lerindo", "Ajido", "Ayinrin", "Adedeji", "Itagunmodi", "Okebode", "Ape", "Olola", "Isa-obi", "Ipole-Ijesa", "Erin-ijesa", "Odo-oja", "Ere-ijewa", "Iwoye-Ijesa", "Esa-Odo", "Iwaraja", "Idomina", "Ibokun", "Otan-Ile", "Ilare", "Liase", "Ilahun", "Iponda", "Ikinyinmi", "Ipetu-Ile", "Wasimi", "Arowojobe", "Yakarin", "Yekami", "Ara Osa", "Oke Awo", "Osi-Soko", "Moremi", "Onigbodogi", "Omifunfun", "Oyera", "Tonkera", "Wakajaye", "Ode Yinka", "Asipa", "Akinlalu", "Fakunle", "Oke-Bale", "Mackay Street", "Araromi Street", "Fagbewesa", "Ota-Efun", "Ireloju", "Igbana Aiyetoro", "Okua", "Agbeye", "Obagun", "Eko-Ende", "Olupona", "Okinni", "Eripa", "Sekona", "Awo", "Ara", "Iragberi", "Agodo", "Isoko", "Masifa", "Ogunro", "Abere", "Songbe", "Aba-ope", "Aagba", "Ororuwo", "Adegbodu", "Ode-Osi", "Ogbagba", "Pelemu", "Oke-Afo", "Ekusa", "Ila-Odo", "Ijabe", "Oke-Ode", "Oke-Ri", "Ekisin", "Okiri", "Mateje", "Faji", "Iyeku"),
		
		"kwara" => array("Ilorin", "Kaima", "Okuta", "Afon", "Jebba", "Bacita", "Moro", "Lafiagi", "Pategi", "Offa", "Erin Ile", "Igbaja", "Ilala", "Ajasse Ipo", "Ipee", "Igosun", "Ikotun", "Irra", "Ojoku", "Ijagbo", "Omuaran", "Oro", "Agbamu", "Aradun", "Osi", "Eruku", "Iloffa", "Oke Onigbin", "Edidi", "Esie", "Iwo Isin", "Owu Isin", "Ijara Isin", "Isanlu Isin", "Ifelodun", "Ilofin Fufu", "Ilota", "Iponrin", "Oke-oyi", "Amoyo", "Omupo", "ARMTI", "Elerinjare", "Ilota", "Babaloma", "Idera", "Owode Ofaro", "Oro-Ago", "Oke-Ode", "Okeya", "Babanla", "Labaika Oja", "Shonga", "Ora Igbomina", "Sanmora", "Oko Iresa", "Agbonda", "Iludun-Oro", "Aran-Orin", "Idofin", "Ekanmeje", "Obo Aiyegunle", "Oke Opin", "Alla", "Oke Aba", "Olla", "Shao"),
		
		"kogi" => array("Lokoja", "Okene", "Ajaokuta", "Ogori", "Ihima", "Itakpe", "Kabba", "Iyara", "Iffe Ijumu", "Ekinrin Adde", "Iyamoye", "Egbeda", "Aiyetoro Gbede", "Isanlu", "Mopa", "Ejiba", "Egbe", "Ejuku", "Odo Ere", "Amuro", "Anyigba", "Idah", "Ankpa", "Abejukolo", "Okpo", "Dekina", "Egume", "Ejule", "Oguma", "Onyedega", "Okaba", "Imane", "Inye", "Awo", "Ogodu", "Ejinya", "Ankpa", "Ofugo", "Elubi", "Itama", "Agbenema", "Bagaji", "Agoleju", "Bagana", "Okuro O", "Ogugu Iyale"),
		
		"edo" => array("Benin", "Ehor", "Ologbo", "Abudu", "Igbanke", "Sobe", "Benin", "Auchi", "Agenebode", "Igarra", "Agbede", "Jattu Uzairue", "Fugar", "Sabongida Ora", "Afuze", "Ekpoma", "Uromi", "Ubiaja", "Iruekpen", "Irrua", "Ewu", "Igueben", "Ebelle", "Ewohimi", "Ekpoma", "Ahia", "Agenebode", "Emu", "Awato", "Ewohimi", "Ewossa", "Illushi", "Ekpon", "Chordus", "Okhuesan", "Oria", "Udakpa", "Ugboha", "Ukpilla", "Ogbido", "Apana", "Aviele", "Aiyegunle", "Akowa", "Ayogwiri", "Afashio", "Iyora", "Iraokho", "Epkeri", "Ogbona", "Okpe", "Iviukhua", "Ivilokpodi", "Ibienafa", "Ivbaro", "Warrake", "Ubuneke", "Unemenekhua", "Uokha", "Arokho", "Otuo", "Aka", "Ikhin", "Emal"),
		
		"delta" => array("Asaba", "Illah", "Ibusa", "Okaman", "Akwukwu", "Ogwashi", "Ubulu-Uku", "Nsukwa", "Ewulu", "Isheagu", "Obior", "Issele-Uku", "Issele-Mkpittime", "Ebu", "Ezi", "Onicha-Ugbo", "Onicha-Olona", "Idumuje-Ugboko", "Agbor", "Owanta", "Ekuku-Agbor", "Umunede", "Igbodo", "Owa-Alero", "Owa-Oyitu", "Idumuesah", "Kwale", "Ndemili", "Umutu", "Ashaka", "Amai", "Ossissa", "Obiaruku", "Ughelli", "Agbarbo", "Orogun", "Ewo-Urhobe", "Kokori", "Eku", "Abraka", "Oghara", "Ekekpanre", "Omore", "Otibio", "Otor-Owhe", "Okpe-Isoko", "Oleh", "Ozoro", "Igbide", "Warri", "Koko", "Sapele", "Adagbarassa", "DSC", "Owain", "Burutu", "Forcados", "Effurun", "Aviara"),
		
		"ondo" => array("Akure", "Itaogbolu", "Iju", "Idanre", "Ilaramokin", "Ijare", "Igbara Oke", "Ondo", "Ore", "Ile Oluji", "Okitipupa", "Ode Aye", "Igbotako", "Ilutitun", "Oniparaga", "Araromi Obu", "Ugbonla", "Okeigbo", "Bamikemon", "Odotu", "Igbokoda", "Owo", "Ikare", "Arigidi", "Irun", "Oke Agbe", "Ajowa", "Ogbagi", "Oka", "Iwaro", "Epinmi", "Akungba", "Oba", "Ifon", "Idonani", "Imeri", "Ijebu Owo", "Ipe", "Ikaramu", "Futa Community", "Oluwatuyi", "Ijapo", "Oba Ile", "Afo", "Ibaka", "Ifira", "Ipesi", "Ikun", "Isua", "Oka", "Supare", "Afin", "Akunnu Ikaram", "Auga", "Ese", "Erusu", "Gedegede", "Ibaram", "Iboropa", "Igasi", "Oyin", "Ikare", "Ifon", "Lepele", "Emure Ile", "Idoani", "Iyere", "Ute", "Ondo West", "Akinjagunla", "Bolorunduro", "College road", "Fagbo", "Igba", "Igunshiu", "Lisanu", "Oruru", "Oboto", "Odigbo", "Owena", "Erinje", "Ikoya", "Igbekebo", "Irele", "Sabomi", "Kbobomi", "Mahinido", "Kajola", "Igbo Egungun", "Igbotu"),
		
		"ekiti" => array("Ado Ekiti", "Ikere", "Erinjinyan", "Efon Alaye", "Ijan", "Ilawe", "Igede", "Osi", "Iluomoba", "Ido", "Aramako", "Ise", "Orin", "Emure", "Awo", "Okemesi", "Igbaraodo", "Iyin", "Ifaki", "Iye", "Ikole", "Omuo Obadore", "Oye", "Ode", "Ilupeju", "Ijero", "Aiyetoro", "Aisegba", "Iloro", "Ilogbo", "Usi", "Omuo-Oke", "Isan", "Ijesa-Isu", "Ayede", "Ire", "Otun", "Igbemo", "Iworoko", "Odo Ado", "Ifisin", "Esure", "Ayegbaju", "Iropora", "Efon Alaaye", "Iludun", "Igogo", "Ikun Ekiti", "Erimope", "Isan", "Erio", "Ijero", "Ido Ajinare", "Ipoti", "Imesi Ekiti", "Itaji", "Itapaji", "Ayedun", "Ayebode", "Araromi", "Egbe", "Oke Ako", "Agbado", "Itapa", "Isinbode", "Ikere", "Ise", "Orun", "Ikogosi", "Ipole", "Ilasa", "Are"),
		
		"enugu" => array("9th Mile", "Adani", "Affa", "Agbani", "Amecet Enugu", "Ogurute", "UNEC", "Okpara Avenue", "Ugwuaji Layout", "Independence Layout", "Inyi", "Iwollo", "Nenwe", "Nsukka", "UNN Campus", "Umuabi", "Obollo Apor", "Ogbede", "Coal Camp", "Oji River", "Udi", "Umulokpa", "Enugu", "Achi", "Ogui Nike", "Isigwu Umana", "Ugwueko Nike", "Affa Street", "Ugbo Odgwu", "Akegbe Ugwu", "Abakpa Nike", "Amauzam", "Agi Town", "Ogui Nike", "Isiagwumana Town", "Enugu Town", "Abakpa Nike Town", "Akagbe Town", "Ochi Street", "Akpawfu Town", "Isiugwu Town", "Transekulu", "Amechi Town", "Oghe", "Abia Town", "Agabi", "Igagwa-Ani Town", "Isigwe Town", "Off Agbani Road", "Awkunanaw", "Ngwo", "Thinkers Corner", "Abakaliki Road", "Effium", "Adaogba", "Opi Town", "Omogwu Umana", "Oruku", "Oye Abbi", "Umubo", "Ituku/Ozalla", "Ugbo Oghe", "Ugwuoba", "Umulumgbe", "Abor Twon", "Amgu Town", "Awgu Town", "Ukana Town"),
		
		"anambra" => array("Nnewi", "Nnobi", "Osumenyi", "Ekwulumili", "Nnokwa", "Alor", "Amichi", "Nkwo Ezinifite", "Ichi", "Unubi", "Ojoto", "Ojoto-Uno", "Agulu", "Adazi Ani", "Adazi Enu", "Adazi", "Aguluzigbo", "Akwaeze", "Enugwu Adazi", "Ichida", "Mbaukwu", "Neni", "Nri", "Obeledu", "Awgbu", "Oraukwu", "Aguata", "Achina", "Igboukwu", "Isuofia", "Uga", "Amaokpala", "Umuchu", "Nanka", "Ajalli", "Ufuma", "EN Abor", "Umuonyiba", "Ndiopkalaeze", "Oko", "Umunze", "Ogbunka", "Ezira", "Ihite", "Isulo", "Akpu", "Awka", "Amawbia", "Nawfia", "Enugu Ukwu", "Nimo", "Unizik", "Achalla", "Mgbakwu", "Ebenene", "Nibo", "Nise", "Abagana", "Ifite Ukpa", "Ezi-Abba", "Enugwu-Agidi", "Nteje", "Awkuzu", "Nawgu", "Ukwulu", "Onitsha", "Fegge", "Odigi", "Obosi", "Nkpor", "Aforigwe", "Nkwelle-Ezunaka", "Umunya", "Atani", "Ogbunike", "Nkpor", "Umunachi", "Umuoji", "Eziowelle", "Abatete", "Oba", "Ogboefere", "Omor", "Ihiala", "Ihala", "Uli", "Oraifite", "Ozuhulu", "Ukpor", "Awka Etiti", "Azigbo", "Oraifite", "Azia", "Orsu", "Ihembosi", "Isseke", "Lilu", "Mbosi", "Nkwo-Ogba", "Ogwuanioch-Ocha", "Umudala", "Umuohi", "Ekwusigo", "Ofute-Zixton", "Umuhu", "Umunakor", "Umuaama", "Odekpe", "Ogwuikpele", "Akili Ogidi", "Aguleri", "Umueri", "Nsugbe", "Nzam", "Awada", "Umuoba Anam", "Okpoko", "Ochanja", "Ogbezalla", "Onitsha", "Oroma Eitit", "CMS", "Ekeobamkpa", "Umuoba Anama", "Odekpe", "Okpoko", "Nkitaku", "Ifite Ogwari", "Ogidi", "Abacha", "Ideani", "Akpo", "Umuona", "Ekwulobia", "Oraeri", "Ikenga", "Umuomaku", "Ezinifita", "Nidiowu", "Ndikelionwu", "Enugwu-Umuonyia", "Ogboji", "Enugwu-Nanka", "Awanigbo", "Eziagu", "Orumba", "Ezukala", "Omogho", "Umueji", "Umuogem", "Awka-Etiti", "Azigbo", "Utuh", "Ebenator", "Akwaihedi", "Isuaniocha", "Umuawulu", "Aforisiagu", "Nimo", "Amansea", "Enugwu-Ukwu", "Ugbene", "Amawbia", "Awba-Ofemili", "Igbakwu", "Nando", "Igariam", "Umumbo"),
		
		"abia" => array("Aba", "Omba", "Owerinta", "Okpuala Ngwa", "Nsuku", "Imo River", "Amapu Ntigha", "Umunkpeyi", "Umuosu", "Umuahia", "Old Umuahia", "Ubakala", "Isiala", "Uzuakoli", "Bende", "Ovim", "Ekenobizi", "N Okaniuga", "Ozuabam", "Olokoro", "Ariam", "Umungasi", "Ohafia", "Arochukwu", "Abiriba", "Ihechiwa", "Igbere", "Alayi", "Item", "Ania", "Ohafia", "Eluama", "Ozuitem"),
		
		"imo" => array("Arji", "Akabo", "Akokwa", "Aladinma", "Ahiara", "Amaigbo", "Amaraku", "Amuzu", "Anara", "Arondizuogu", "Atta", "Awo-Ommamma", "Chokoneze", "Egbema", "Ehime", "Ekwereazu", "Enyiogugu", "Etiti", "Umuguma", "Ihiagwa", "Eziobodi", "Ife-Ezinihitte", "Ihiagwa", "Iho", "Ikeduru", "Obowu", "Okpala", "Izombe", "Ibiasoegbe", "Mbaitoli", "Mbieri", "Mgbidi", "Nempi", "Ngor", "Nkwerre", "Nkwogwu", "Nsu", "Obizi", "Obudiagwa", "Ogbe", "Oguta", "Okigwe", "Opala", "Obube", "Okpofe", "Okwudor", "Onicha-Ezi", "Orlu", "Orus-Obodo", "Osina", "Obowu", "New Owerri", "Owerri", "Amadugba", "Umuaka", "Umuneke", "Umunoha", "Urualla", "Uzoagba", "Abba", "Achara", "Isiokpo", "Amainyita", "Amumara", "Asa Ubirielem", "Emekuku", "Eziama Obiato", "Eziudo", "Ihitte Owerri", "Isiekenaezi", "Isunjaba", "Itu Eziihitte", "Obile Ohaji", "Obosima", "Ogwa", "Omuma", "Ovuru", "Ulakwo", "Amandugba", "Naze", "Mbieri", "Umulogho", "Umunama", "Emii", "Umuduru"),
		
		"ebonyi" => array("Uburu", "Okposi", "Ishiagu", "Unwana", "Afikpo", "Abakaliki", "Onu. Echara", "Onueke", "Egunkwo"),
		
		"rivers" => array("Port Harcourt", "University of Port Harcourt", "Buguma", "Kalabari", "Rumuola", "Amagwa", "Rumuolumeni", "Abonnema", "Okehi 2", "Elekahia", "Woji", "Ogale", "Bori", "Okirika", "Bodo", "Opobo", "Ahoada", "Omoku Town", "Elele", "Abua", "Ndoni"),
		
		"akwa ibom" => array("Abak", "Awa Iman", "Oron", "Afaha Nsit", "Edeobom", "Eket", "Enwang", "Ete", "Etinan", "Ikot Akpan Abia", "Ikot Ekwere", "Ikot Ekpene", "Ikot abasi", "Ikot Ubo", "Itu", "Mbiaso", "Mbioto", "Nwaniba", "Afaha Etok", "Okobo", "Oron", "Oyubia", "University of Uyo", "Uyo"),
		
		"cross river" => array("Ambo", "Bansara", "Bendeghe Ekiem", "Calabar", "Creek Town", "Edgerly Road", "Housing Estate", "Ikom", "Kakwagom", "Obudu", "Obubra", "Ogoja", "Unical", "Wanakom", "Polycal", "Epz", "Itigidi", "Biakpan", "Ohong", "Ikoneto", "Abangork", "Abankang", "Abanwana Erei", "Adiabo Akurikang", "Adun Town", "Bashua Boki", "Bateriko", "Bekwara", "Federal Housing Estate", "Kakwagom", "Ugep", "Usumotong"),
		
		"bayelsa" => array("Yenagoa", "Brass", "Nembe", "Ogbia", "Sagbama", "Ahoada", "Elele", "Abua", "Ndoni"),
		
		"borno" => array("Maiduguri", "Kukawa", "Monguno", "Ngala", "Marte", "Binishek", "Dikwa", "Bama", "Askira", "Gwoza", "Konduga", "Lassa", "Biu", "Damboa", "Shani", "Marama", "Yimirshika", "Sakwa", "Wandali"),
		
		"yobe" => array("Damaturu", "Nguru", "Potiskum", "Gashua", "Gaidam", "Damagum", "Yadi Buni", "Gida", "Gadaka", "Jakusko"),
		
		"adamawa" => array("Ganye", "Jada", "Yola South", "Jimeta", "Song", "Fufore", "Mayo Belwa", "Demsa", "Numan", "Lafiya", "Shelleng", "Guyuk", "Borrong", "Mubi", "Dumne", "Girei", "Army Barracks", "Dashen", "Bare", "Kwambula", "Kuva-gaya", "Zekun", "Pella", "Maiha", "Kola", "Banjiram", "Ga’anda", "Guyaku", "Bille"),
		
		"taraba" => array("Jalingo", "Zing", "Pantisawa", "Lua Town", "Karim Lamido", "Mutum Biyu", "Bali", "Serti", "Gembu", "Wukari", "Takum", "Ibi", "Donga", "Baissa", "Mararaba", "Jen"),
		
		"kano" => array("Kano", "Panisau", "Gezawa", "Minjibir", "Dawakin Tofa", "Danbatta", "Takai", "Garko", "Gaya", "Wudil", "Rijiyar Zaki", "Panshekara", "Madobi", "Rano", "Garun Mallam", "Bebeji", "Gwarzo", "Kiru", "Tudun Wada"),
		
		"jigawa" => array("Babura", "Birnin Kudu", "Birniwa", "Dutse", "Garki", "Gumel", "Hadejia", "Kiyawa", "Mallam Modori", "Ringim", "Dutse", "Auyo", "Gagarawa", "Jahun", "Kafin Hausa", "Kaugama", "Kirikasamma", "Maigatari", "Roni", "Taura", "Katanga", "Shuwarin"),
		
		"bauchi" => array("Bauchi", "Dass", "Tafawa Balewa", "Toro", "Ningi", "Alkaleri", "Gadam", "Azare", "Itas", "Jama’are", "Yana", "Darazo", "Misau", "Kari", "Gamawa", "Kafin Madaki", "Nabordo", "Shadawanka", "Yankari Park"),
		
		"gombe" => array("Gombe", "Funakaye", "Billiri", "Dukku", "Akko", "Yamaltu Deba", "Balanga", "Kwami", "Kaltungo", "Balanga", "Shongom", "Cham Kandiyo", "Dadin Kowa", "Gwandum"),
		
		"kaduna" => array("Kaduna", "Jaji", "Birnin Gwari", "Kasuwan Magani", "Zaria", "Makarfi", "Ikara", "Dutsen Wai", "Soba", "Giwa", "Kafanchan", "Kagoro", "Zonkwa", "Zongon Kataf", "Kaura", "Madakiya", "Kwoi", "Kubacha", "Kachia", "Gwantu", "Godogogo", "Kafanchan", "Jaji", "Saminaka"),
		
		"katsina" => array("Katsina", "Daura", "Dutsinma", "Mani", "Funtua", "Malumfashi", "Bakori", "Faskari"),
		
		"sokoto" => array("Sokoto", "Mabera", "Kwannawa", "Dundaye", "Farfaru", "Bado", "Bodinga", "Illela", "Gwadabawa", "Wurno", "Tambawal", "Yabo", "Shagari", "Isa", "Gidan Madi"),
		
		"kebbi" => array("Birnin Kebbi", "Argungu", "Jega", "Kangiwa", "Gwandu", "Bunza", "Dandi", "Gesse", "Zuru", "Yauri", "Koko", "Dirin Daji", "Bagudo", "Aliero", "Zuru"),
		
		"zamfara" => array("Gummi", "Gusau", "Kauran Namoda", "Isa", "Shinkafi", "Chafe", "Maru", "Talatan Mafara", "Anka", "Bungudu", "Kucheri"),
		
		"abuja" => array("Karu", "Nyanya", "Garki", "Garki II", "Asokoro", "Bwari", "Kubwa", "DeiDei", "Wuse", "Wuse II", "Gwagwalada", "Abaji", "Kwali", "Lugbe", "Gwagwa", "Idukarmo"),
		
		"niger" => array("Minna", "Kuta", "Wushishi", "Zungeru", "Sarkin Pawa", "Bosso", "Bida", "Lapai", "Kutigi", "Kataregi", "Badeggi", "Katcha", "Baro", "Kontagora", "Rijau", "Kagara", "Mokwa", "New Bussa", "Suleja"),
		
		"plateau" => array("Jos", "Bukuru", "Vom", "Kuru", "Barkin Ladi", "Kurra Falls", "Daffo", "Pankshin", "Mangu", "Gindiri", "Kabwir", "Shendam", "Langtang", "Amper", "Dengi", "Wase", "Mabudi", "Yelwa", "Demshin", "Garkawa", "Denmak", "Tudun Wada", "Bokkos", "Heipang", "Gazum"),
		
		"nasarawa" => array("Lafia", "Agyaragu", "Obi", "Keana", "Eggon", "Akwanga", "Gudi", "Wamba", "Assakio", "Barkin Abdullahi"),
		
		"benue" => array("Makurdi", "Aliade", "Daudu", "Otukpo", "Okpoga", "Ugbokolo", "Oju", "Adoka", "Orokam", "Iga Okpaya", "Allan-Ejor", "Gboko", "Mbagwa", "Katsina Ala", "Adikpo", "Vandeikya", "Makurdi District")
	);

	$towns = array();
	foreach ($statesAndTowns as $stateKey => $townsVal) {
		if ($state === $stateKey) {
			$towns = $townsVal;
		}
	}
	
	// Sort the towns in alphabetical order
	asort($towns);

	$output = "";
	// Select value is set to empty, so that validation function will be able to identify if a town is not selected
	$output .= "<option value=''>Select</option>";
	foreach ($towns as $town) {
		$output .= "<option value='".lcfirst(str_replace(" ", "_", $town))."'>".ucfirst($town)."</option>";
	}
	$output .= "<option value='other'>Other</option>";
	
	return $output;
}

// Javascirpt function
/*
function getTowns(state, town) {
	// Get the state id
	var stateId = document.getElementById(state);
	// var stateId = $('#state');
	var townId = document.getElementById(town);
	var stateVal = stateId.value;

	// Create a multidimensional array with the states and towns
	var statesAndTowns = [
		["lagos", ["Ajeromi", "Trade Fair", "Amuwo Odofin", "Badagry", "Ejinrin", "Erodo", "Agbowa", "Ijebu", "Lekki", "Ikorodu Rural", "Irepodun", "Ojo", "Ajangbadi Afromedia", "Okokomaiko", "Igbo Elerin", "Ajangbadi Ikemba House", "Ilemba Awori", "Igbede", "Ilogbo", "Shibiri Ekune", "Iba Town New Site", "Olojo", "Ira", "Alaba", "Maryland", "Alausa", "Ogba Aguda", "Ojodu", "Isheri Oke", "Ifako Agege", "Iju Water Works", "Iju Isaga", "Oworosoki", "Oworosoki L and K", "Abule", "Shomolu Central", "Anthony", "Shomolu Pedro", "Gbagada", "Atunrase Estate Agbagada", "Ojota", "Ketu", "Alapere Ketu", "Ketu Orisigun", "Ikosi", "Ketu Mile 12", "Magodo", "Oremeji Ifako", "Onipanu", "Mushin", "Lawanson", "Oshodi", "Isolo", "Ilasamaja", "Ejigbo Orile Owo", "Ikotun", "Ijegun", "Igando", "Egan", "Obadore", "Idimu", "Ikeja", "Murtala Muhammed Airport", "Dopemu", "Oya Estate Police Barracks", "Alimosho", "Abule Egba", "Ipaja", "Allen", "Ikeja Oba Akran", "Agege", "Oko Oba Agege", "Olota", "Akintan", "Jankara", "Ojokoro", "Alagbado", "Ahmadiya", "Suberu Oje", "Meiran", "Alakuko", "Ijare", "Agbelekale", "Aboru", "Oke Odo", "Ebute Meta West", "Yaba/Ebute Meta East", "Onike", "Balogun", "Dolphin Estate", "Lafiaji", "Itafaji", "Isale Eko", "Oke Arin", "Epe Tedo", "Obalende", "Agarawu", "Idumagbo", "Onikan", "Ikoyi", "Victoria Island", "1004 Area", "Maroko Extension", "Ajah", "Apapa Central", "Apapa North", "Apapa South", "Apapa West", "Ijora Oloye", "Orile Igamu", "Surulere Central", "Ajegunle Boundary", "Amukoko Alaba Oro", "Amukoko West", "Amukoko East", "Amukoko North", "Bakare Faro", "Ijora Badia East", "Ijora Badia Central", "Ijora Badai West", "Ijora Badia North", " CI Sari Iganmu Orile South", "Sari Iganmu Orile North", "Olodi Apapa", "FESTAC Community 1", "FESTAC Community 2", "FESTAC Community 3", "FESTAC Community 4", "Olute", "Satellite Town", "Alakija", "Site C", "Ilaje", "Kirikiri Phase 3", "Kirikiri Area", "Kirikiri Industrial Area", "Ajara Agamathem Vetho", "Ibereko", "Asago Sango", "Hospital Road Ascon", "Seme Badagry Iyafin", "Oba", "Isele 1", "Lowa Estate", "Ogolonto", "Owutu", "Abule Agbon", "Ori Okuta", "Isawo", "Dagbolu", "Eyita", "Lambo Lasunwom village", "Federal Lowcost Housing Estate", "Jala Dugbo Area", "Kokoroabu", "Sabo", "Isele", "Anibaba", "Aleje", "Gbasemo", "Solebo Area", "Aga", "Ipakodo"]],
		
		["ogun", ["Abeokuta", "Totoro", "Alabata Area", "Odeda", "Osiele Abeokuta", "Ayetoro", "Imeko", "Ibara Area", "Owode", "Owode-Yewa", "Ilaro", "Oke-Odan", "Ijebu Ode", "Odobolu", "Ijebu Mushin", "Isonyin", "Abigi", "Ogbere", "Ayila", "Aiyepe", "Odogbolu", "Ijebu Igbo", "Ago Iwoye", "Oru", "Sagamu", "Iperu", "Ikenne", "Isara", "Oderemo", "Ilisan", "Obafemi Owode", "Imeko", "Abeokuta", "Ogunmakin", "Igbore", "Etega", "Opeji", "Itoko Abeokuta", "Alamala Abk", "Oba", "Alapako", "Iboro", "Imasai", "Ibeshe", "Igan Alade", "Oja Odan", "Igbogila", "Ajilete", "Ipokia", "Idiroko", "Joga Orile", "Obalende", "Itele", "Iperin", "Iwopin", "Ibiade", "Imodi-Imosan", "Ogbogbo", "Ososa", "Itese", "New Road", "Awa", "Ilishan", "Ijokun", "Emuren", "Ilara", "Ilisan", "Sagamu", "Makun", "Ogere", "Ogijo", "Ajegunle", "Ejino", "Sagamu", "Irolu Remo", "Ipara"]],
		
		["oyo", ["Dugbe", "Oja Oba", "Eleyele", "Oyo", "Isokun", "Awe", "Iseyin", "Igbojaye", "Okeho", "Saki", "Tade", "Sepeteri", "Ago Are", "Ago Amodu", "Takie", "Arowomole", "Ajaawa", "Ikoyi Ile", "Kishi", "Igbeti", "Oba Ago", "Igbo Ora", "Tapa", "Ibadan", "Ilora", "Agunpopo", "Fiditi", "Iseyin", "Adoawaye", "Ayetoro-oke", "Apete", "Ijaiye", "Ikoloba", "Total Garden", "Ilua", "Isemi-Ile", "Okaka", "Ipapo", "Otu", "Komu", "Iganna", "Idiko-Ile", "Iwere-ile", "Ijio"]],
		
		["osun", ["Osogbo", "Ede", "Ifon Osun", "Iwo", "Ejigbo", "Ile-Ogbo", "Olla", "Kuta", "Iragbiji", "Iresi", "Igbajo", "Ada", "Ikirun", "Iba", "Okuku", "Oyan", "Igbaye", "Ilobu", "Erin-Osin", "Iree", "Otan-Ayegbaju", "Ila", "Oke-Ija", "Ora", "Inisa", "Ekosin", "Ile-Ife", "Garrage", "Olode", "Ifetedo", "OAU", "Moro", "Edunabon", "Ipetumodu", "Modakeke", "Gbongan", "Ode-Omu", "Orile-Owu", "Ikire", "Apomu", "Ilesa", "Iwara", "Osu", "Ifewara", "Ibokun", "Imesi-Ile", "Esa-Oke", "Ijebu-Jesa", "Iloko", "Erinmo", "Eti-Oni", "Igangan", "Lerindo", "Ajido", "Ayinrin", "Adedeji", "Itagunmodi", "Okebode", "Ape", "Olola", "Isa-obi", "Ipole-Ijesa", "Erin-ijesa", "Odo-oja", "Ere-ijewa", "Iwoye-Ijesa", "Esa-Odo", "Iwaraja", "Idomina", "Ibokun", "Otan-Ile", "Ilare", "Liase", "Ilahun", "Iponda", "Ikinyinmi", "Ipetu-Ile", "Wasimi", "Arowojobe", "Yakarin", "Yekami", "Ara Osa", "Oke Awo", "Osi-Soko", "Moremi", "Onigbodogi", "Omifunfun", "Oyera", "Tonkera", "Wakajaye", "Ode Yinka", "Asipa", "Akinlalu", "Fakunle", "Oke-Bale", "Mackay Street", "Araromi Street", "Fagbewesa", "Ota-Efun", "Ireloju", "Igbana Aiyetoro", "Okua", "Agbeye", "Obagun", "Eko-Ende", "Olupona", "Okinni", "Eripa", "Sekona", "Awo", "Ara", "Iragberi", "Agodo", "Isoko", "Masifa", "Ogunro", "Abere", "Songbe", "Aba-ope", "Aagba", "Ororuwo", "Adegbodu", "Ode-Osi", "Ogbagba", "Pelemu", "Oke-Afo", "Ekusa", "Ila-Odo", "Ijabe", "Oke-Ode", "Oke-Ri", "Ekisin", "Okiri", "Mateje", "Faji", "Iyeku"]],
		
		["kwara", ["Ilorin", "Kaima", "Okuta", "Afon", "Jebba", "Bacita", "Moro", "Lafiagi", "Pategi", "Offa", "Erin Ile", "Igbaja", "Ilala", "Ajasse Ipo", "Ipee", "Igosun", "Ikotun", "Irra", "Ojoku", "Ijagbo", "Omuaran", "Oro", "Agbamu", "Aradun", "Osi", "Eruku", "Iloffa", "Oke Onigbin", "Edidi", "Esie", "Iwo Isin", "Owu Isin", "Ijara Isin", "Isanlu Isin", "Ifelodun", "Ilofin Fufu", "Ilota", "Iponrin", "Oke-oyi", "Amoyo", "Omupo", "ARMTI", "Elerinjare", "Ilota", "Babaloma", "Idera", "Owode Ofaro", "Oro-Ago", "Oke-Ode", "Okeya", "Babanla", "Labaika Oja", "Shonga", "Ora Igbomina", "Sanmora", "Oko Iresa", "Agbonda", "Iludun-Oro", "Aran-Orin", "Idofin", "Ekanmeje", "Obo Aiyegunle", "Oke Opin", "Alla", "Oke Aba", "Olla", "Shao"]],
		
		["kogi", ["Lokoja", "Okene", "Ajaokuta", "Ogori", "Ihima", "Itakpe", "Kabba", "Iyara", "Iffe Ijumu", "Ekinrin Adde", "Iyamoye", "Egbeda", "Aiyetoro Gbede", "Isanlu", "Mopa", "Ejiba", "Egbe", "Ejuku", "Odo Ere", "Amuro", "Anyigba", "Idah", "Ankpa", "Abejukolo", "Okpo", "Dekina", "Egume", "Ejule", "Oguma", "Onyedega", "Okaba", "Imane", "Inye", "Awo", "Ogodu", "Ejinya", "Ankpa", "Ofugo", "Elubi", "Itama", "Agbenema", "Bagaji", "Agoleju", "Bagana", "Okuro O", "Ogugu Iyale"]],
		
		["edo", ["Benin", "Ehor", "Ologbo", "Abudu", "Igbanke", "Sobe", "Benin", "Auchi", "Agenebode", "Igarra", "Agbede", "Jattu Uzairue", "Fugar", "Sabongida Ora", "Afuze", "Ekpoma", "Uromi", "Ubiaja", "Iruekpen", "Irrua", "Ewu", "Igueben", "Ebelle", "Ewohimi", "Ekpoma", "Ahia", "Agenebode", "Emu", "Awato", "Ewohimi", "Ewossa", "Illushi", "Ekpon", "Chordus", "Okhuesan", "Oria", "Udakpa", "Ugboha", "Ukpilla", "Ogbido", "Apana", "Aviele", "Aiyegunle", "Akowa", "Ayogwiri", "Afashio", "Iyora", "Iraokho", "Epkeri", "Ogbona", "Okpe", "Iviukhua", "Ivilokpodi", "Ibienafa", "Ivbaro", "Warrake", "Ubuneke", "Unemenekhua", "Uokha", "Arokho", "Otuo", "Aka", "Ikhin", "Emal"]],
		
		["delta", ["Asaba", "Illah", "Ibusa", "Okaman", "Akwukwu", "Ogwashi", "Ubulu-Uku", "Nsukwa", "Ewulu", "Isheagu", "Obior", "Issele-Uku", "Issele-Mkpittime", "Ebu", "Ezi", "Onicha-Ugbo", "Onicha-Olona", "Idumuje-Ugboko", "Agbor", "Owanta", "Ekuku-Agbor", "Umunede", "Igbodo", "Owa-Alero", "Owa-Oyitu", "Idumuesah", "Kwale", "Ndemili", "Umutu", "Ashaka", "Amai", "Ossissa", "Obiaruku", "Ughelli", "Agbarbo", "Orogun", "Ewo-Urhobe", "Kokori", "Eku", "Abraka", "Oghara", "Ekekpanre", "Omore", "Otibio", "Otor-Owhe", "Okpe-Isoko", "Oleh", "Ozoro", "Igbide", "Warri", "Koko", "Sapele", "Adagbarassa", "DSC", "Owain", "Burutu", "Forcados", "Effurun", "Aviara"]],
		
		["ondo", ["Akure", "Itaogbolu", "Iju", "Idanre", "Ilaramokin", "Ijare", "Igbara Oke", "Ondo", "Ore", "Ile Oluji", "Okitipupa", "Ode Aye", "Igbotako", "Ilutitun", "Oniparaga", "Araromi Obu", "Ugbonla", "Okeigbo", "Bamikemon", "Odotu", "Igbokoda", "Owo", "Ikare", "Arigidi", "Irun", "Oke Agbe", "Ajowa", "Ogbagi", "Oka", "Iwaro", "Epinmi", "Akungba", "Oba", "Ifon", "Idonani", "Imeri", "Ijebu Owo", "Ipe", "Ikaramu", "Futa Community", "Oluwatuyi", "Ijapo", "Oba Ile", "Afo", "Ibaka", "Ifira", "Ipesi", "Ikun", "Isua", "Oka", "Supare", "Afin", "Akunnu Ikaram", "Auga", "Ese", "Erusu", "Gedegede", "Ibaram", "Iboropa", "Igasi", "Oyin", "Ikare", "Ifon", "Lepele", "Emure Ile", "Idoani", "Iyere", "Ute", "Ondo West", "Akinjagunla", "Bolorunduro", "College road", "Fagbo", "Igba", "Igunshiu", "Lisanu", "Oruru", "Oboto", "Odigbo", "Owena", "Erinje", "Ikoya", "Igbekebo", "Irele", "Sabomi", "Kbobomi", "Mahinido", "Kajola", "Igbo Egungun", "Igbotu"]],
		
		["ekiti", ["Ado Ekiti", "Ikere", "Erinjinyan", "Efon Alaye", "Ijan", "Ilawe", "Igede", "Osi", "Iluomoba", "Ido", "Aramako", "Ise", "Orin", "Emure", "Awo", "Okemesi", "Igbaraodo", "Iyin", "Ifaki", "Iye", "Ikole", "Omuo Obadore", "Oye", "Ode", "Ilupeju", "Ijero", "Aiyetoro", "Aisegba", "Iloro", "Ilogbo", "Usi", "Omuo-Oke", "Isan", "Ijesa-Isu", "Ayede", "Ire", "Otun", "Igbemo", "Iworoko", "Odo Ado", "Ifisin", "Esure", "Ayegbaju", "Iropora", "Efon Alaaye", "Iludun", "Igogo", "Ikun Ekiti", "Erimope", "Isan", "Erio", "Ijero", "Ido Ajinare", "Ipoti", "Imesi Ekiti", "Itaji", "Itapaji", "Ayedun", "Ayebode", "Araromi", "Egbe", "Oke Ako", "Agbado", "Itapa", "Isinbode", "Ikere", "Ise", "Orun", "Ikogosi", "Ipole", "Ilasa", "Are"]],
		
		["enugu", ["9th Mile", "Adani", "Affa", "Agbani", "Amecet Enugu", "Ogurute", "UNEC", "Okpara Avenue", "Ugwuaji Layout", "Independence Layout", "Inyi", "Iwollo", "Nenwe", "Nsukka", "UNN Campus", "Umuabi", "Obollo Apor", "Ogbede", "Coal Camp", "Oji River", "Udi", "Umulokpa", "Enugu", "Achi", "Ogui Nike", "Isigwu Umana", "Ugwueko Nike", "Affa Street", "Ugbo Odgwu", "Akegbe Ugwu", "Abakpa Nike", "Amauzam", "Agi Town", "Ogui Nike", "Isiagwumana Town", "Enugu Town", "Abakpa Nike Town", "Akagbe Town", "Ochi Street", "Akpawfu Town", "Isiugwu Town", "Transekulu", "Amechi Town", "Oghe", "Abia Town", "Agabi", "Igagwa-Ani Town", "Isigwe Town", "Off Agbani Road", "Awkunanaw", "Ngwo", "Thinkers Corner", "Abakaliki Road", "Effium", "Adaogba", "Opi Town", "Omogwu Umana", "Oruku", "Oye Abbi", "Umubo", "Ituku/Ozalla", "Ugbo Oghe", "Ugwuoba", "Umulumgbe", "Abor Twon", "Amgu Town", "Awgu Town", "Ukana Town"]],
		
		["anambra", ["Nnewi", "Nnobi", "Osumenyi", "Ekwulumili", "Nnokwa", "Alor", "Amichi", "Nkwo Ezinifite", "Ichi", "Unubi", "Ojoto", "Ojoto-Uno", "Agulu", "Adazi Ani", "Adazi Enu", "Adazi", "Aguluzigbo", "Akwaeze", "Enugwu Adazi", "Ichida", "Mbaukwu", "Neni", "Nri", "Obeledu", "Awgbu", "Oraukwu", "Aguata", "Achina", "Igboukwu", "Isuofia", "Uga", "Amaokpala", "Umuchu", "Nanka", "Ajalli", "Ufuma", "EN Abor", "Umuonyiba", "Ndiopkalaeze", "Oko", "Umunze", "Ogbunka", "Ezira", "Ihite", "Isulo", "Akpu", "Awka", "Amawbia", "Nawfia", "Enugu Ukwu", "Nimo", "Unizik", "Achalla", "Mgbakwu", "Ebenene", "Nibo", "Nise", "Abagana", "Ifite Ukpa", "Ezi-Abba", "Enugwu-Agidi", "Nteje", "Awkuzu", "Nawgu", "Ukwulu", "Onitsha", "Fegge", "Odigi", "Obosi", "Nkpor", "Aforigwe", "Nkwelle-Ezunaka", "Umunya", "Atani", "Ogbunike", "Nkpor", "Umunachi", "Umuoji", "Eziowelle", "Abatete", "Oba", "Ogboefere", "Omor", "Ihiala", "Ihala", "Uli", "Oraifite", "Ozuhulu", "Ukpor", "Awka Etiti", "Azigbo", "Oraifite", "Azia", "Orsu", "Ihembosi", "Isseke", "Lilu", "Mbosi", "Nkwo-Ogba", "Ogwuanioch-Ocha", "Umudala", "Umuohi", "Ekwusigo", "Ofute-Zixton", "Umuhu", "Umunakor", "Umuaama", "Odekpe", "Ogwuikpele", "Akili Ogidi", "Aguleri", "Umueri", "Nsugbe", "Nzam", "Awada", "Umuoba Anam", "Okpoko", "Ochanja", "Ogbezalla", "Onitsha", "Oroma Eitit", "CMS", "Ekeobamkpa", "Umuoba Anama", "Odekpe", "Okpoko", "Nkitaku", "Ifite Ogwari", "Ogidi", "Abacha", "Ideani", "Akpo", "Umuona", "Ekwulobia", "Oraeri", "Ikenga", "Umuomaku", "Ezinifita", "Nidiowu", "Ndikelionwu", "Enugwu-Umuonyia", "Ogboji", "Enugwu-Nanka", "Awanigbo", "Eziagu", "Orumba", "Ezukala", "Omogho", "Umueji", "Umuogem", "Awka-Etiti", "Azigbo", "Utuh", "Ebenator", "Akwaihedi", "Isuaniocha", "Umuawulu", "Aforisiagu", "Nimo", "Amansea", "Enugwu-Ukwu", "Ugbene", "Amawbia", "Awba-Ofemili", "Igbakwu", "Nando", "Igariam", "Umumbo"]],
		
		["abia", ["Aba", "Omba", "Owerinta", "Okpuala Ngwa", "Nsuku", "Imo River", "Amapu Ntigha", "Umunkpeyi", "Umuosu", "Umuahia", "Old Umuahia", "Ubakala", "Isiala", "Uzuakoli", "Bende", "Ovim", "Ekenobizi", "N Okaniuga", "Ozuabam", "Olokoro", "Ariam", "Umungasi", "Ohafia", "Arochukwu", "Abiriba", "Ihechiwa", "Igbere", "Alayi", "Item", "Ania", "Ohafia", "Eluama", "Ozuitem"]],
		
		["imo", ["Arji", "Akabo", "Akokwa", "Aladinma", "Ahiara", "Amaigbo", "Amaraku", "Amuzu", "Anara", "Arondizuogu", "Atta", "Awo-Ommamma", "Chokoneze", "Egbema", "Ehime", "Ekwereazu", "Enyiogugu", "Etiti", "Umuguma", "Ihiagwa", "Eziobodi", "Ife-Ezinihitte", "Ihiagwa", "Iho", "Ikeduru", "Obowu", "Okpala", "Izombe", "Ibiasoegbe", "Mbaitoli", "Mbieri", "Mgbidi", "Nempi", "Ngor", "Nkwerre", "Nkwogwu", "Nsu", "Obizi", "Obudiagwa", "Ogbe", "Oguta", "Okigwe", "Opala", "Obube", "Okpofe", "Okwudor", "Onicha-Ezi", "Orlu", "Orus-Obodo", "Osina", "Obowu", "New Owerri", "Owerri", "Amadugba", "Umuaka", "Umuneke", "Umunoha", "Urualla", "Uzoagba", "Abba", "Achara", "Isiokpo", "Amainyita", "Amumara", "Asa Ubirielem", "Emekuku", "Eziama Obiato", "Eziudo", "Ihitte Owerri", "Isiekenaezi", "Isunjaba", "Itu Eziihitte", "Obile Ohaji", "Obosima", "Ogwa", "Omuma", "Ovuru", "Ulakwo", "Amandugba", "Naze", "Mbieri", "Umulogho", "Umunama", "Emii", "Umuduru"]],
		
		["ebonyi", ["Uburu", "Okposi", "Ishiagu", "Unwana", "Afikpo", "Abakaliki", "Onu. Echara", "Onueke", "Egunkwo"]],
		
		["rivers", ["Port Harcourt", "University of Port Harcourt", "Buguma", "Kalabari", "Rumuola", "Amagwa", "Rumuolumeni", "Abonnema", "Okehi 2", "Elekahia", "Woji", "Ogale", "Bori", "Okirika", "Bodo", "Opobo", "Ahoada", "Omoku Town", "Elele", "Abua", "Ndoni"]],
		
		["akwa ibom", ["Abak", "Awa Iman", "Oron", "Afaha Nsit", "Edeobom", "Eket", "Enwang", "Ete", "Etinan", "Ikot Akpan Abia", "Ikot Ekwere", "Ikot Ekpene", "Ikot abasi", "Ikot Ubo", "Itu", "Mbiaso", "Mbioto", "Nwaniba", "Afaha Etok", "Okobo", "Oron", "Oyubia", "University of Uyo", "Uyo"]],
		
		["cross river", ["Ambo", "Bansara", "Bendeghe Ekiem", "Calabar", "Creek Town", "Edgerly Road", "Housing Estate", "Ikom", "Kakwagom", "Obudu", "Obubra", "Ogoja", "Unical", "Wanakom", "Polycal", "Epz", "Itigidi", "Biakpan", "Ohong", "Ikoneto", "Abangork", "Abankang", "Abanwana Erei", "Adiabo Akurikang", "Adun Town", "Bashua Boki", "Bateriko", "Bekwara", "Federal Housing Estate", "Kakwagom", "Ugep", "Usumotong"]],
		
		["bayelsa", ["Yenagoa", "Brass", "Nembe", "Ogbia", "Sagbama", "Ahoada", "Elele", "Abua", "Ndoni"]],
		
		["borno", ["Maiduguri", "Kukawa", "Monguno", "Ngala", "Marte", "Binishek", "Dikwa", "Bama", "Askira", "Gwoza", "Konduga", "Lassa", "Biu", "Damboa", "Shani", "Marama", "Yimirshika", "Sakwa", "Wandali"]],
		
		["yobe", ["Damaturu", "Nguru", "Potiskum", "Gashua", "Gaidam", "Damagum", "Yadi Buni", "Gida", "Gadaka", "Jakusko"]],
		
		["adamawa", ["Ganye", "Jada", "Yola South", "Jimeta", "Song", "Fufore", "Mayo Belwa", "Demsa", "Numan", "Lafiya", "Shelleng", "Guyuk", "Borrong", "Mubi", "Dumne", "Girei", "Army Barracks", "Dashen", "Bare", "Kwambula", "Kuva-gaya", "Zekun", "Pella", "Maiha", "Kola", "Banjiram", "Ga’anda", "Guyaku", "Bille"]],
		
		["taraba", ["Jalingo", "Zing", "Pantisawa", "Lua Town", "Karim Lamido", "Mutum Biyu", "Bali", "Serti", "Gembu", "Wukari", "Takum", "Ibi", "Donga", "Baissa", "Mararaba", "Jen"]],
		
		["kano", ["Kano", "Panisau", "Gezawa", "Minjibir", "Dawakin Tofa", "Danbatta", "Takai", "Garko", "Gaya", "Wudil", "Rijiyar Zaki", "Panshekara", "Madobi", "Rano", "Garun Mallam", "Bebeji", "Gwarzo", "Kiru", "Tudun Wada"]],
		
		["jigawa", ["Babura", "Birnin Kudu", "Birniwa", "Dutse", "Garki", "Gumel", "Hadejia", "Kiyawa", "Mallam Modori", "Ringim", "Dutse", "Auyo", "Gagarawa", "Jahun", "Kafin Hausa", "Kaugama", "Kirikasamma", "Maigatari", "Roni", "Taura", "Katanga", "Shuwarin"]],
		
		["bauchi", ["Bauchi", "Dass", "Tafawa Balewa", "Toro", "Ningi", "Alkaleri", "Gadam", "Azare", "Itas", "Jama’are", "Yana", "Darazo", "Misau", "Kari", "Gamawa", "Kafin Madaki", "Nabordo", "Shadawanka", "Yankari Park"]],
		
		["gombe", ["Gombe", "Funakaye", "Billiri", "Dukku", "Akko", "Yamaltu Deba", "Balanga", "Kwami", "Kaltungo", "Balanga", "Shongom", "Cham Kandiyo", "Dadin Kowa", "Gwandum"]],
		
		["kaduna", ["Kaduna", "Jaji", "Birnin Gwari", "Kasuwan Magani", "Zaria", "Makarfi", "Ikara", "Dutsen Wai", "Soba", "Giwa", "Kafanchan", "Kagoro", "Zonkwa", "Zongon Kataf", "Kaura", "Madakiya", "Kwoi", "Kubacha", "Kachia", "Gwantu", "Godogogo", "Kafanchan", "Jaji", "Saminaka"]],
		
		["katsina", ["Katsina", "Daura", "Dutsinma", "Mani", "Funtua", "Malumfashi", "Bakori", "Faskari"]],
		
		["sokoto", ["Sokoto", "Mabera", "Kwannawa", "Dundaye", "Farfaru", "Bado", "Bodinga", "Illela", "Gwadabawa", "Wurno", "Tambawal", "Yabo", "Shagari", "Isa", "Gidan Madi"]],
		
		["kebbi", ["Birnin Kebbi", "Argungu", "Jega", "Kangiwa", "Gwandu", "Bunza", "Dandi", "Gesse", "Zuru", "Yauri", "Koko", "Dirin Daji", "Bagudo", "Aliero", "Zuru"]],
		
		["zamfara", ["Gummi", "Gusau", "Kauran Namoda", "Isa", "Shinkafi", "Chafe", "Maru", "Talatan Mafara", "Anka", "Bungudu", "Kucheri"]],
		
		["abuja", ["Karu", "Nyanya", "Garki", "Garki II", "Asokoro", "Bwari", "Kubwa", "DeiDei", "Wuse", "Wuse II", "Gwagwalada", "Abaji", "Kwali", "Lugbe", "Gwagwa", "Idukarmo"]],
		
		["niger", ["Minna", "Kuta", "Wushishi", "Zungeru", "Sarkin Pawa", "Bosso", "Bida", "Lapai", "Kutigi", "Kataregi", "Badeggi", "Katcha", "Baro", "Kontagora", "Rijau", "Kagara", "Mokwa", "New Bussa", "Suleja"]],
		
		["plateau", ["Jos", "Bukuru", "Vom", "Kuru", "Barkin Ladi", "Kurra Falls", "Daffo", "Pankshin", "Mangu", "Gindiri", "Kabwir", "Shendam", "Langtang", "Amper", "Dengi", "Wase", "Mabudi", "Yelwa", "Demshin", "Garkawa", "Denmak", "Tudun Wada", "Bokkos", "Heipang", "Gazum"]],
		
		["nasarawa", ["Lafia", "Agyaragu", "Obi", "Keana", "Eggon", "Akwanga", "Gudi", "Wamba", "Assakio", "Barkin Abdullahi"]],
		
		["benue", ["Makurdi", "Aliade", "Daudu", "Otukpo", "Okpoga", "Ugbokolo", "Oju", "Adoka", "Orokam", "Iga Okpaya", "Allan-Ejor", "Gboko", "Mbagwa", "Katsina Ala", "Adikpo", "Vandeikya", "Makurdi District"]],
	];

	var towns = [];
	
	// search for the towns within the state specified
	for (var i = 0; i < statesAndTowns.length; i++) {
		// This checks if the selected state is found in the array 
		// states and town and retrives the towns
		// Also the states with underscore is removed before searching
		if (statesAndTowns[i][0] === stateVal.replace(/_/g, ' ')) {
			towns = statesAndTowns[i][1];
			console.log(towns);
		}
	}
	
	// populate the multiple select menu of the town
	populateTown(towns, townId);
} */

?>