<?php

	/*	FIGYELEM!!!
	 *
	 *	Tilos print vagy echo függvényt használni, mert akkor hiábs lehet a meghívott jsondata parse máshol!
	 *	Bár lehet ha a php dob hibát, akkor szintén ugyanaz lesz a helyzet...
	 *
	 */

// EDZO
	/*	Mielőtt hozzáadunk egy új edzőt az adatbázishoz
	 *
	 *	\param id			az edző azonosítója
	 */
	function UjEdzoHozzaadasaElott($id) {
//		print "uj edzo hozzaadasa elott: ".$data->vnev. " ".$data->knev."<br>";
	}

	/*	Miután hozzáadtunk egy új edzőt az adatbázishoz
	 *
	 *	\param sikeres		igaz, ha a felvitel során nem jelenkezett hiba.
	 *	\param data			tartalma: id, foto, vnev, knev, rovid_nev, alcim, leiras, ertekeles
	 */
	function UjEdzoHozzaadasaUtan($sikeres, $data) {
//		print "uj edzo hozzaadva: ".$data->vnev. " ".$data->knev."<br>";
	}

	/*	Mielőtt módosítunkg egy edzőt az adatbázisban
	 *
	 *	\param id			az edző azonosítója
	 */
	function EdzoModositasaElott($id) {
//		print "edzo modositasa elott: ".$data->vnev. " ".$data->knev."<br>";
	}

	/*	Miután módosítottunk egy edzőt az adatbázisban
	 *
	 *	\param sikeres		igaz, ha a felvitel során nem jelenkezett hiba.
	 *	\param data			tartalma: id, foto, vnev, knev, rovid_nev, alcim, leiras, ertekeles
	 */
	function EdzoModositasaUtan($sikeres, $data) {
//		print "edzo modositva: ".$data->vnev. " ".$data->knev."<br>";
	}




// ORA
	/*	Mielőtt hozzáadunk egy új órát az adatbázishoz
	 *
	 *	\param id			az óra azonosítója
	 */
	function UjOraHozzaadasaElott($id) {
//		print "uj ora hozzaadasa elott: ".$data->nev."<br>";
	}

	/*	Miután hozzáadtunk egy új órát az adatbázishoz
	 *
	 *	\param sikeres		igaz, ha a felvitel során nem jelenkezett hiba.
	 *	\param data			tartalma: id, foto, logo, nev, rovid_nev, alcim, leiras, max_letszam, perc, belepodij, color
	 */
	function UjOraHozzaadasaUtan($sikeres, $data) {
//		print "uj ora hozzaadva: ".$data->nev."<br>";
	}

	/*	Mielőtt módosítunkg egy órát az adatbázisban
	 *
	 *	\param id			az óra azonosítója
	 */
	function OraModositasaElott($id) {
//		print "ora modositasa elott: ".$data->nev."<br>";
	}

	/*	Miután módosítottunk egy órát az adatbázisban
	 *
	 *	\param sikeres		igaz, ha a felvitel során nem jelenkezett hiba.
	 *	\param data			tartalma: id, foto, logo, nev, rovid_nev, alcim, leiras, max_letszam, perc, belepodij, color
	 */
	function OraModositasaUtan($sikeres, $data) {
//		print "ora modositva: ".$data->nev."<br>";
	}




// TEREM
	/*	Mielőtt hozzáadunk egy új termet az adatbázishoz
	 *
	 *	\param id			a terem azonosítója
	 */
	function UjTeremHozzaadasaElott($id) {
//		print "uj terem hozzaadasa elott: ".$data->nev."<br>";
	}

	/*	Miután hozzáadtunk egy új termet az adatbázishoz
	 *
	 *	\param sikeres		igaz, ha a felvitel során nem jelenkezett hiba.
	 *	\param data			tartalma: id, foto, nev, alcim, foglalhato
	 */
	function UjTeremHozzaadasaUtan($sikeres, $data) {
//		print "uj terem hozzaadva: ".$data->nev."<br>";
	}

	/*	Mielőtt módosítunkg egy termet az adatbázisban
	 *
	 *	\param id			a terem azonosítója
	 */
	function TeremModositasaElott($id) {
//		print "terem modositasa elott: ".$data->nev."<br>";
	}

	/*	Miután módosítottunk egy termet az adatbázisban
	 *
	 *	\param sikeres		igaz, ha a felvitel során nem jelenkezett hiba.
	 *	\param data			tartalma: id, foto, nev, alcim, foglalhato
	 */
	function TeremModositasaUtan($sikeres, $data) {
//		print "terem modositva: ".$data->nev."<br>";
	}




// Naptar
// Mármint itt naptárbejegyzésekről van szó, tehát nem a foglalásokkal kapcsolatos delegate funkciók érkeznek be ide...
// TODO: lehet, hogy nem csak az id-t kellene visszavárni a bme.js-ben
	/*	Mielőtt hozzáadunk egy új naptárat az adatbázishoz
	 *
	 *	\param id			a naptár azonosítója
	 */
	function UjNaptarHozzaadasaElott($id) {
//		print "uj naptar hozzaadasa elott: ".$data->id."<br>";
	}

	/*	Miután hozzáadtunk egy új naptárat az adatbázishoz
	 *
	 *	\param sikeres		igaz, ha a felvitel során nem jelenkezett hiba.
	 *	\param data			tartalma: id
	 */
	function UjNaptarHozzaadasaUtan($sikeres, $data) {
//		print "uj naptar hozzaadva: ".$data->id."<br>";
	}

	/*	Mielőtt módosítunkg egy naptárat az adatbázisban
	 *
	 *	\param id			a naptár azonosítója
	 */
	function NaptarModositasaElott($id) {
//		print "naptar modositasa elott: ".$data->id."<br>";
	}

	/*	Miután módosítottunk egy naptárat az adatbázisban
	 *
	 *	\param sikeres		igaz, ha a felvitel során nem jelenkezett hiba.
	 *	\param data			tartalma: id
	 */
	function NaptarModositasaUtan($sikeres, $data) {
//		print "naptar modositva: ".$data->id."<br>";
	}



// TODO: ide kellenek még a foglalásokkal kapcsolatos értesítések

?>
