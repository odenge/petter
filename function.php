<?php
/* 設定ファイルを読み込む */
require_once("set_up.php");

/* 管理データベースパス */
define("DATABASE", "db/db.csv");

/******************************************************/
/* データベース管理クラス */
/******************************************************/

/* 管理画面用 */
class DatabaseManage {
    private $id;
    private $title;
    private $order;
    private $new;
    private $design;
    private $article_number;
    private $url;

	/* 引数に指定したデータベースIDの記事情報で、メンバ変数を初期化する */
    function __construct($id){
		$this->id = $id;
		$db_file = DATABASE;
		
		if (file_exists($db_file)) {

			$fp = fopen($db_file, "rb");

			if ($fp) {
				if (flock($fp, LOCK_SH)) {

					while (!feof($fp)) {
						$line_data = fgets($fp);
						$line_array = explode("\t", $line_data);

						if($line_array[0] == $this->id) {						
							$line_array[1] = $this->changeOutputData($line_array[1]);
							$this->title = $line_array[1];
							$this->order = $line_array[2];
							$this->new = $line_array[3];
							$this->design = $line_array[4];
							$this->article_number = $line_array[5];
							$this->url = $line_array[6];
							break;
						}
					}

					flock($fp, LOCK_UN);
				}
				else {
					print("ファイルロックに失敗しました");
				}
			}

			fclose($fp);

		}

    }


	/* データベースからHTMLを画面に表示するときの変換処理をする */
	function changeOutputData($outputData) {
		$outputData = str_replace("\\t", "\t", $outputData);		
		$outputData = str_replace("\\n", "<br />", $outputData);
		return($outputData);
	}


    function getId(){
		return $this->id;
    }
    function getTitle(){
		return $this->title;
    }
    function getOrder(){
		return $this->order;
    }
    function getNew(){
		return $this->new;
    }
    function getDesign(){
		return $this->design;
    }
    function getArticleNumber(){
		return $this->article_number;
    }
    function getUrl(){
		return $this->url;
    }

}





/* PETTER組み込みページ用 */
class PetterDatabaseManage extends DatabaseManage {

    private $id;
    private $title;
    private $order;
    private $new;
    private $design;
    private $article_number;

	/* 引数に指定したデータベースIDの記事情報で、メンバ変数を初期化する */
    function __construct($petter_path, $id){
		$this->id = $id;
		$db_file = $petter_path."/".DATABASE;
		
		if (file_exists($db_file)) {

			$fp = fopen($db_file, "rb");

			if ($fp) {
				if (flock($fp, LOCK_SH)) {

					while (!feof($fp)) {
						$line_data = fgets($fp);
						$line_array = explode("\t", $line_data);

						if($line_array[0] == $this->id) {						
							$line_array[1] = $this->changeOutputData($line_array[1]);
							$this->title = $line_array[1];
							$this->order = $line_array[2];
							$this->new = $line_array[3];
							$this->design = $line_array[4];
							$this->article_number = $line_array[5];
							break;
						}
					}

					flock($fp, LOCK_UN);
				}
				else {
					print("ファイルロックに失敗しました");
				}
			}

			fclose($fp);

		}

    }

	/* データベースからHTMLを画面に表示するときの変換処理をする */
	function changeOutputData($outputData) {
		$outputData = str_replace("\\t", "\t", $outputData);		
		$outputData = str_replace("\\n", "<br />", $outputData);
		return($outputData);
	}


    function getId(){
		return $this->id;
    }
    function getTitle(){
		return $this->title;
    }
    function getOrder(){
		return $this->order;
    }
    function getNew(){
		return $this->new;
    }
    function getDesign(){
		return $this->design;
    }
    function getArticleNumber(){
		return $this->article_number;
    }

}










/******************************************************/
/* 記事管理クラス */
/******************************************************/

class ArticleManage {
    private $db_id;
    private $article_id;
    private $title;
    private $contents;
    private $time;


	/* 引数に指定したデータベースIDの記事情報で、メンバ変数を初期化する */
    function __construct($db_id, $article_id){
		$this->db_id = $db_id;
		$this->article_id = $article_id;
		$db_file = "db/".$db_id."/article.csv";
		
		if (file_exists($db_file)) {

			$fp = fopen($db_file, "rb");

			if ($fp) {
				if (flock($fp, LOCK_SH)) {
					while (!feof($fp)) {
						$line_data = fgets($fp);
						$line_array = explode("\t", $line_data);

						if($line_array[1] == $this->article_id) {						
							$this->title = $this->changeOutputData($line_array[2]);
							$this->contents = $this->changeOutputData($line_array[3]);
							$this->time = $line_array[4];
							break;
						}
					}

					flock($fp, LOCK_UN);
				}
				else {
					print("ファイルロックに失敗しました");
				}
			}

			fclose($fp);

		}

    }


	/* データベースからHTMLを画面に表示するときの変換処理をする */
	function changeOutputData($outputData) {
		$outputData = str_replace("\\t", "\t", $outputData);		
		$outputData = str_replace("\\n", "<br />", $outputData);
		return($outputData);
	}


    function getDbId(){
		return $this->db_id;
    }
    function getArticleId(){
		return $this->article_id;
    }
    function getTitle(){
		return $this->title;
    }
    function getContents(){
		return $this->contents;
    }
    function getTime(){
		return $this->time;
    }
}










/******************************************************/
/* 管理画面表示処理 */
/******************************************************/

/* データベースの内容を読み込む */
function readData($db_file, $case) {

	if (file_exists($db_file)) {
		$fp = fopen($db_file, "rb");

		if ($fp) {
			if (flock($fp, LOCK_SH)) {



	/************** 場合分け処理 ↓↓↓↓↓***************************************************************/
			switch ($case) {



				/* ■管理データベースの一覧表示 ↓↓↓↓↓ */
				case "default":

					while (!feof($fp)) {
						$line_data = fgets($fp);
						$line_array = explode("\t", $line_data);

						if($line_array[1] != ""){
							$line_array[1] = changeOutputData($line_array[1]);
							$id = $line_array[0];
							$title = $line_array[1];
							require("template/db_list_loop.php");
						}
					}
					
					break;
				/* ■管理データベースの一覧表示 ↑↑↑↑↑ */




				/* ■記事の一覧表示 ↓↓↓↓↓ */
				case "article_list":
					
					/* ページ表示のために無視する記事数 */
					$ignore_article_number = ($_GET["page"] - 1) * PAGE_ARTICLE_NUMBER;
					

					/* データベースの1行を1つの要素とした配列にする */
					$data_array = file($db_file);


					/* 投稿時間でソートされた配列を作成 */
					$sort_array = array();
					/* データベースの行がキーとなる（最初の行が0行とする）[データベースID,記事ID,タイトル,本文,投稿時間のデータが格納された配列] */
					foreach($data_array as $key => $line_data) {
						$line_array = explode("\t", $line_data);
						$sort_array[$key] = $line_array;		
					}
					foreach ($sort_array as $key) $time[] = intval($key['4']);/* 投稿時間でソート */
					/* 投稿時間の昇順 */
					if($order != 0) {
						/* 投稿時間の昇順に並び変わった、データベースの1行を1つの要素とした配列にする */
						array_multisort($time, SORT_ASC, $sort_array);
					}
					/* 投稿時間の降順 */
					else {
						/* 投稿時間の降順に並び変わった、データベースの1行を1つの要素とした配列にする */
						array_multisort($time, SORT_DESC, $sort_array);
					}


					$loop_number = PAGE_ARTICLE_NUMBER;
					foreach($sort_array as $key => $line_array) {
						if($line_array[2] != ""){
							if ($ignore_article_number > 0 ) {
								$ignore_article_number -= 1;
							}
							else {
								$db_id = $line_array[0];
								$article_id = $line_array[1];
								$title = changeOutputData($line_array[2]);
								$contents = changeOutputData($line_array[3]);
								$timestamp = $line_array[4];

								require("template/article_list_loop.php");
								$loop_number -= 1;
								if ($loop_number == 0) {
									break;
								
								}
							}
						}
					}
					
					break;
				/* ■記事の一覧表示 ↑↑↑↑↑ */



			}
	/************** 場合分け処理 ↑↑↑↑↑***************************************************************/


				
				flock($fp, LOCK_UN);

			}
			else {
				print("ファイルロックに失敗しました");
			}
		}
	}

	else {
		$fp = fopen($db_file, "wb");
	}

	fclose($fp);
}










/******************************************************/
/* PETTER組み込み画面表示処理 */
/******************************************************/

/* PETTERで管理している記事を、ウェブサイトに表示する */
function petter($petter_path, $db_id) {

	$petter_path = $petter_path;

	/* 【管理データベースクラス作成】　指定した管理データベースIDの内容を操作するクラス*/
	$database_obj = new PetterDatabaseManage($petter_path ,$db_id);
	/* newマークの有無 */
	$new = $database_obj->getNew();
	/* 記事の表示順 */
	$order = $database_obj->getOrder();
	/* 記事の表示数 */
	$article_number = $database_obj->getArticleNumber();
	/* デザイン */
	$design = $database_obj->getDesign();
	
			
	/* 表示する記事データベースのファイル */
	$db_file = $petter_path."/db/".$db_id."/article.csv";

	if (file_exists($db_file)) {
		$fp = fopen($db_file, "rb");

		if ($fp) {
			/* データベースの1行を1つの要素とした配列にする */
			$data_array = file($db_file);

			if (flock($fp, LOCK_SH)) {
				
				/* デザインテンプレートを読み込む */
				$design_file = "design/".$design.".php";



				/* 投稿時間でソートされた配列を作成 */
				$sort_array = array();
				/* データベースの行がキーとなる（最初の行が0行とする）[データベースID,記事ID,タイトル,本文,投稿時間のデータが格納された配列] */
				foreach($data_array as $key => $line_data) {
					$line_array = explode("\t", $line_data);
					$sort_array[$key] = $line_array;		
				}
				foreach ($sort_array as $key) $time[] = intval($key['4']);/* 投稿時間でソート */
				/* 投稿時間の降順 */
				/* 投稿時間の降順に並び変わった、データベースの1行を1つの要素とした配列にする */
				array_multisort($time, SORT_DESC, $sort_array);
				/* 最新投稿日時取得 */
				$new_time = "";
				foreach($sort_array as $key => $line_array) {
					$timestamp = $line_array[4];
					$new_time = date("Y,m,d", $timestamp);
					break;
				}
				/* 現在のタイムスタンプ取得 */
				$now_time = time();


				/* 最新の投稿が下に表示される時　投稿時間の昇順にソート */
				if($order != 0) {
					/* 投稿時間の昇順に並び変わった、データベースの1行を1つの要素とした配列にする */
					array_multisort($time, SORT_ASC, $sort_array);
				}


				/* 表示データ件数測定と表示配列作成 */
				$display_array = array();
				$all_article_number = 0;
				foreach($sort_array as $key => $line_array) {
					if($line_array[2] != ""){
							$display_array[] = $line_array;
							$all_article_number += 1;
					}
				}
				if($article_number > $all_article_number) {
					$article_number = $all_article_number;
				}
				





				/* ■PETTER表示　↓↓↓↓↓ */
				print('<table cellpadding="0" cellspacing="0" class="petter">');


				/* ●表示件数制限有り */
				if($article_number != 0) {

					/* 表示　下が最新の記事 */
					if ($order) {
						
						/* 表示させない記事数 */
						$ignore_number = $all_article_number - $article_number;
						
						foreach($display_array as $key => $line_array) {

							if(!($ignore_number > 0)) {
									$db_id = $line_array[0];
									$article_id = $line_array[1];
									$title = changeOutputData($line_array[2]);
									$contents = changeOutputData($line_array[3]);
									$timestamp = $line_array[4];

									require($design_file);							
									$article_number -= 1;
							}

							$ignore_number -= 1;

						}

					}
					/* 表示　上が最新の記事 */
					else {

						foreach($display_array as $key => $line_array) {
							if($article_number > 0){
								$db_id = $line_array[0];
								$article_id = $line_array[1];
								$title = changeOutputData($line_array[2]);
								$contents = changeOutputData($line_array[3]);
								$timestamp = $line_array[4];

								require($design_file);							
								$article_number -= 1;
							}
						}

					}

				}
				/* ●表示件数制限無し */
				else {

					$article_number = $all_article_number;

					foreach($display_array as $key => $line_array) {
						$db_id = $line_array[0];
						$article_id = $line_array[1];
						$title = changeOutputData($line_array[2]);
						$contents = changeOutputData($line_array[3]);
						$timestamp = $line_array[4];

						require($design_file);
						$article_number -= 1;
					}	

				}

				print('</table>');
				/* ■PETTER表示　↑↑↑↑↑ */







				flock($fp, LOCK_UN);

			}
			else {
				print("ファイルロックに失敗しました");
			}
		}
	fclose($fp);

	}
	else {
		print("PETTERのパスとIDの設定を確認してください。");
	}

}




/* PETTERで管理している記事のRSSリンク */
function petter_rss($petter_path, $db_id) {
	print($petter_path."/rss/".$db_id."/rss.xml");
}







/******************************************************/
/* 文字の変換処理 */
/******************************************************/

/* 半角と全角のスペースを取り除く */
function changeSpace($inputData) {
	$inputData = str_replace("　", "", $inputData);
	$inputData = str_replace(" ", "", $inputData);
	return($inputData);
}

/* フォームから入力されたHTMLのエスケープ処理等をする */
function changeInputData($inputData) {
	$inputData = htmlentities($inputData, ENT_QUOTES, "UTF-8");
	$inputData = str_replace("\t", "\\t", $inputData);
	$inputData = str_replace("\r\n", "\\n", $inputData);
	return($inputData);
}

/* データベースからHTMLを画面に表示するときの変換処理をする */
function changeOutputData($outputData) {
	$outputData = str_replace("\\t", "\t", $outputData);		
	$outputData = str_replace("\\n", "<br />", $outputData);
	$outputData = str_replace("&lt;a", "<a", $outputData);
	$outputData = str_replace("&gt;", ">", $outputData);
	$outputData = str_replace("&lt;/a", "</a", $outputData);
	$outputData = str_replace("&quot;", "\"", $outputData);
	return($outputData);
}

/* データベースから編集画面に編集する内容を表示するときの変換処理をする */
function changeEditOutputData($outputData) {
	$outputData = str_replace("\\t", "\t", $outputData);		
	$outputData = str_replace("\\n", "\n", $outputData);
	return($outputData);
}

/* 編集画面に表示する内容で<br />を改行と変換する */
function changeEditOutputBr($outputData) {
	$outputData = str_replace("<br />", "\n", $outputData);		
	return($outputData);
}

/* データベースからRSSの要約を配信画面で表示するための変換処理をする */
function changeRssOutput($outputData) {
	$outputData = html_entity_decode($outputData, ENT_QUOTES, "UTF-8");
	$outputData = str_replace("\\t", "", $outputData);
	$outputData = str_replace("\\n", "", $outputData);
	$outputData = str_replace("<", "&lt; ", $outputData);
	$outputData = str_replace( ">", "&gt;", $outputData);
	$outputData = str_replace("&lt; a", "&lt;a", $outputData);
	$outputData = str_replace("&lt; /a", "&lt;/a", $outputData);
	$outputData = str_replace("\"", "&quot;", $outputData);
	return($outputData);
}








/******************************************************/
/* 保存処理 */
/******************************************************/

/* 管理データベースにデータを書き込み　その記事データベースを作成 */
function writeDataDB($db_file) {

	/* 管理データベースID　IDは1から始まる */
	$id = count( file($db_file) ) + 1;
	/* 管理する内容 */
	$title = changeInputData($_POST["title"]);
	/* 表示する順番 */
	if($_POST["order"]) {
		$order = $_POST["order"];
	}
	else {
		$order = 0;
	}
	/* newマーク表示 */
	if($_POST["new"]) {
		/* 有り */
		$new = 1;
	}
	else {
		/* 無し */
		$new = 0;
	}
	/* デザインテンプレート */
	if($_POST["design"]) {
		$design = $_POST["design"];
	}
	else {
		$design = 1;
	}
	/* 表示件数 */
	if($_POST["article_number_set"] == 1) {
		$article_number = mb_convert_kana($_POST["article_number"], "a", "UTF-8"); 
		$article_number = floor($article_number);
	}
	else {
		$article_number = 0;
	}
	/* 表示ページURL */
	$url = changeSpace(changeInputData($_POST["url"]));


	$data = $id."\t".$title."\t".$order."\t".$new."\t".$design."\t".$article_number."\t".$url."\r\n";
	$fp = fopen($db_file, "ab");

	if ($fp) {
		if (flock($fp, LOCK_EX)) {
			if (fwrite($fp, $data) === FALSE) {
				print("ファイル書き込みに失敗しました");
			}
			flock($fp, LOCK_UN);	
		}
		else {
			print("ファイルロックに失敗しました");
		}
	}
	fclose($fp);


	/* 記事データベース作成 */
	mkdir("db/".$id);
	$fp = fopen("db/".$id."/article.csv", "wb");
	if ($fp) {
		if (flock($fp, LOCK_EX)) {
		
			$data = $id."\t"."1"."\t"."PETTER"."\t"."ただいま参上"."\t".time()."\r\n";
		
			if (fwrite($fp, $data) === FALSE) {
				print("ファイル書き込みに失敗しました");
			}
			flock($fp, LOCK_UN);	
		}
		else {
			print("ファイルロックに失敗しました");
		}
	}
	fclose($fp);

	/* RSS作成 */
	mkdir("rss/".$id);
	writeRSS($id);
}





/* 記事データベースにデータを書き込む */
function writeDataArticle($db_file) {

	$db_id = changeInputData($_POST["db_id"]);
	$title = changeInputData($_POST["title"]);
	$contents = changeInputData($_POST["contents"]);
	$year = mb_convert_kana($_POST["year"], "a", "UTF-8");
	$month = mb_convert_kana($_POST["month"], "a", "UTF-8");
	$day = mb_convert_kana($_POST["day"], "a", "UTF-8");
	$hour = mb_convert_kana($_POST["hour"], "a", "UTF-8");
	$minute = mb_convert_kana($_POST["minute"], "a", "UTF-8");
	$second = mb_convert_kana($_POST["second"], "a", "UTF-8");	
	$time = mktime($hour,$minute,$second,$month,$day,$year);

	/* ファイルの行数を取得　ID付けるのにつかう */
	$article_id = count( file($db_file) ) + 1;

	$input_data = $db_id."\t".$article_id."\t".$title."\t".$contents."\t".$time."\r\n";
	$old_data = file_get_contents($db_file);

	$data = $input_data.$old_data;

	$fp = fopen($db_file, "wb");

	if ($fp) {
		if (flock($fp, LOCK_EX)) {
			if (fwrite($fp, $data) === FALSE) {
				print("ファイル書き込みに失敗しました");
			}
			flock($fp, LOCK_UN);	
		}
		else {
			print("ファイルロックに失敗しました");
		}
	}

	fclose($fp);
}










/******************************************************/
/* 編集処理 */
/******************************************************/

/* 管理データベースを編集する */
function editDataDB($db_file) {
	$edit_id = $_POST["id"];
	$edit_title = $_POST["title"];
	$edit_order = $_POST["order"];
	if($_POST["new"] == "") {
		$edit_new = 0;
	}
	else {
		$edit_new = $_POST["new"];
	}
	$edit_design = $_POST["design"];
	if($_POST["article_number"] == "") {
		$edit_article_number = 0;
	}
	else {
		$edit_article_number = $_POST["article_number"];
	}
	$edit_url = $_POST["url"];


	/* データベースの1行を1つの要素とした配列にする */
	$data_array = file(DATABASE);

	/* 編集するIDと同じIDをデータベースから探し、編集後の内容に書き換える */
	foreach($data_array as $key => $line_data) {
		$line_array = explode("\t", $line_data);

		if ($edit_id == $line_array[0]) {
			/* $line_array[0]のIDはそのまま */
			/* 内容 */
			$line_array[1] = changeInputData($edit_title);
			/* 並び順 */
			$line_array[2] = $edit_order;
			/* newマーク */
			$line_array[3] = $edit_new;
			/* デザインテンプレート */
			$line_array[4] = $edit_design;
			/* 表示件数 */
			$line_array[5] = mb_convert_kana($edit_article_number, "a", "UTF-8");
			/* 表示ページURL */
			$edit_url = changeSpace(changeInputData($edit_url));
			$line_array[6] = str_replace(" ", "", $edit_url);

			$line_data = join("\t", $line_array)."\r\n";
			$data_array[$key] = $line_data;
		}
		
	}

	$fp = fopen("$db_file","wb");
	foreach($data_array as $line_data) {
		fwrite($fp, $line_data);
	}
	fclose($fp);
}





/* 個別記事データベースを編集する */
function editDataArticle($db_file, $edit_article_id) {

	$edit_db_id = changeInputData($_POST["db_id"]);
	$edit_title = changeInputData($_POST["title"]);
	$edit_contents = changeInputData($_POST["contents"]);
	$edit_year = mb_convert_kana($_POST["year"], "a", "UTF-8");
	$edit_month = mb_convert_kana($_POST["month"], "a", "UTF-8");
	$edit_day = mb_convert_kana($_POST["day"], "a", "UTF-8");
	$edit_hour = mb_convert_kana($_POST["hour"], "a", "UTF-8");
	$edit_minute = mb_convert_kana($_POST["minute"], "a", "UTF-8");
	$edit_second = mb_convert_kana($_POST["second"], "a", "UTF-8");	
	$edit_time = mktime($edit_hour,$edit_minute,$edit_second,$edit_month,$edit_day,$edit_year);

	/* データベースの1行を1つの要素とした配列にする */
	$data_array = file($db_file);

	/* 編集する記事IDと同じIDをデータベースから探し、編集後の内容に書き換える */
	foreach($data_array as $key => $line_data) {
		$line_array = explode("\t", $line_data);

		if ($edit_article_id == $line_array[1]) {
			/* $line_array[0]の管理データベースIDはそのまま */
			/* $line_array[1]の個別記事データベースIDはそのまま */

			/* タイトル */
			$line_array[2] = changeInputData($edit_title);
			/* 本文 */
			$line_array[3] = $edit_contents;
			/* 時間 */
			$line_array[4] = $edit_time;

			$line_data = join("\t", $line_array)."\r\n";
			$data_array[$key] = $line_data;
		}
		
	}

	$fp = fopen("$db_file","wb");
	foreach($data_array as $line_data) {
		fwrite($fp, $line_data);
	}
	fclose($fp);
}










/******************************************************/
/* 削除処理 */
/******************************************************/

/* 管理データベースのデータを削除し　その記事データベースも削除する */
function deleteDataDB($db_file, $delete_array) {

	/* 管理データベースの1行を1つの要素とした配列にする */
	$data_array = file($db_file);

	/* 削除するデータの分だけ処理を行う */
	foreach($delete_array as $delete_id) {

		/* 削除するIDと同じIDをデータベースから探し、そのデータのタイトルを空にする */
		foreach($data_array as $key => $line_data) {
			$line_array = explode("\t", $line_data);

			if ($delete_id == $line_array[0]) {
				/* 管理内容 */
				$line_array[1] = "";
				/* 表示順 */
				$line_array[2] = "";
				/* newマーク */
				$line_array[3] = "";
				/* デザイン */
				$line_array[4] = "";
				/* 表示記事数 */
				$line_array[5] = "";
				/* 表示ページURL */
				$line_array[6] = "\r\n";
				$line_data = join("\t", $line_array);
				$data_array[$key] = $line_data;

			}
			
		}
		
		
		/* 記事データベースを削除 */
		unlink("db/".$delete_id."/article.csv");
		rmdir("db/".$delete_id);

		/* RSSを削除 */
		unlink("rss/".$delete_id."/rss.xml");
		rmdir("rss/".$delete_id);

	}

	$fp = fopen("$db_file","wb");
	foreach($data_array as $line_data) {
		fwrite($fp, $line_data);
	}
	fclose($fp);
}





/* 個別記事データベースのデータを削除する */
function deleteDataArticle($db_file, $delete_array) {

	/* 個別記事データベースの1行を1つの要素とした配列にする */
	$data_array = file($db_file);

	/* 削除するデータの分だけ処理を行う */
	foreach($delete_array as $delete_id) {

		/* 削除するIDと同じIDをデータベースから探し、そのデータのタイトルを空にする */
		foreach($data_array as $key => $line_data) {
			$line_array = explode("\t", $line_data);

			if ($delete_id == $line_array[1]) {
				/* タイトル */
				$line_array[2] = "";
				/* 本文 */
				$line_array[3] = "";
				/* 時間 */
				$line_array[4] = "\r\n";
				$line_data = join("\t", $line_array);
				$data_array[$key] = $line_data;
			}
			
		}

	}

	$fp = fopen("$db_file","wb");
	foreach($data_array as $line_data) {
		fwrite($fp, $line_data);
	}
	fclose($fp);
}




/******************************************************/
/* 記事一覧　ページリスト表示 */
/******************************************************/

function displayArriclePageList($db_id, $page) {

	$db_file = "db/".$db_id."/article.csv";
	
	/* 表示される記事の総数 */
	$all_article_nubmer = 0;


	if (file_exists($db_file)) {
		$fp = fopen($db_file, "rb");

		if ($fp) {
			if (flock($fp, LOCK_SH)) {

					/* データベースの1行を1つの要素とした配列にする */
					$data_array = file($db_file);
	
					foreach($data_array as $key => $line_data) {
						$line_array = explode("\t", $line_data);
						if($line_array[2] != ""){
							$all_article_nubmer += 1;
						}
					}
					
					/* 表示されるページの総数 */
					$all_page_number = floor($all_article_nubmer / PAGE_ARTICLE_NUMBER) + 1;					
					if ($all_article_nubmer % PAGE_ARTICLE_NUMBER == 0) {
						$all_page_number -= 1;
					}

										
					/* 管理画面にページリストを表示 */
					if ($all_page_number > 1 ) {
						
						print('<ul class="page_list clearfix">');

						for($page_number = 1; $page_number <= $all_page_number; $page_number++) {

							if($page_number == $page) {
								print('<li class="now_page">'.$page_number.'</li>');
							}
							else {
								print('<li><a href="admin.php?data=article_list&db_id='.$db_id.'&page='.$page_number.'">'.$page_number.'</a></li>');
							}
					
						}					

						print('</ul>');

					}

				flock($fp, LOCK_UN);

			}
			else {
				print("ファイルロックに失敗しました");
			}
		}
	}
	fclose($fp);


}







/******************************************************/
/* RSS処理 */
/******************************************************/

/* RSSを作成 */
function writeRSS($db_id) {

	$database_obj = new DatabaseManage($db_id);			
	$article_number = $database_obj->getArticleNumber();
	$order = $database_obj->getOrder();
	$url = $database_obj->getUrl();
	$url = str_replace("\r\n", "", $url);


	$db_file = "db/".$db_id."/article.csv";


	/* RSSのitem項目データ作成 */
	$item_data = "";
	if (file_exists($db_file)) {
		$fp = fopen($db_file, "rb");

		if ($fp) {
			if (flock($fp, LOCK_SH)) {


					/* データベースの1行を1つの要素とした配列にする */
					$data_array = file($db_file);


					/* 投稿時間でソートされた配列を作成 */
					$sort_array = array();
					/* データベースの行がキーとなる（最初の行が0行とする）[データベースID,記事ID,タイトル,本文,投稿時間のデータが格納された配列] */
					foreach($data_array as $key => $line_data) {
						$line_array = explode("\t", $line_data);
						$sort_array[$key] = $line_array;		
					}
					foreach ($sort_array as $key) $time[] = intval($key['4']);/* 投稿時間でソート */

					/* 投稿時間の降順 */
					/* 投稿時間の降順に並び変わった、データベースの1行を1つの要素とした配列にする */
					array_multisort($time, SORT_DESC, $sort_array);




					/* 表示件数制限有り */
					if($article_number != 0) {
	
						foreach($sort_array as $key => $line_array) {
							if($line_array[2] != "" && $article_number > 0){
								$db_id = $line_array[0];
								$article_id = $line_array[1];
								$title = changeRssOutput($line_array[2]);
								$contents = changeRssOutput($line_array[3]);
								$time = date("r", $line_array[4]);
	
							$item_data = $item_data.
'<item>'.
'<title>'.$title.'</title>'.
'<link>'.$url.'#'.$article_id.'</link>'.
'<description>'.$contents.'</description>'.
'<category>更新情報</category>'.
'<pubDate>'.$time.'</pubDate>'.
'</item>';
								$article_number -= 1;
							}
						}
	
					}
					/* 表示件数制限無し */
					else {
	
						foreach($sort_array as $key => $line_array) {
							if($line_array[2] != ""){
								$db_id = $line_array[0];
								$article_id = $line_array[1];
								$title = changeRssOutput($line_array[2]);
								$contents = changeRssOutput($line_array[3]);
								$time = date("r", $line_array[4]);
	
							$item_data = $item_data.
'<item>'.
'<title>'.$title.'</title>'.
'<link>'.$url."#".$article_id.'</link>'.
'<description>'.$contents.'</description>'.
'<category>更新情報</category>'.
'<pubDate>'.$time.'</pubDate>'.
'</item>';
							}
						}	

					}




				flock($fp, LOCK_UN);

			}
			else {
				print("ファイルロックに失敗しました");
			}
		}
	}
	fclose($fp);




	$data = '<?xml version="1.0" encoding="UTF-8"?>'.
'<rss version="2.0" xmlns:rss="http://purl.org/rss/1.0/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom">'.
'<channel>'.
'<title>'.$database_obj->getTitle().'</title>'.
'<link>'.$url.'</link>'.
'<description>'.$database_obj->getTitle().'の更新情報です。</description>'.
'<language>ja</language>'.
'<generator>PETTER generator v1.0</generator>'.
'<category>contents</category>'.
'<docs>http://backend.userland.com/rss</docs>'.
$item_data.
'</channel>'.
'</rss>';



	/* RSSファイル作成 */
	$fp = fopen("rss/".$db_id."/rss.xml", "wb");
	if ($fp) {
		if (flock($fp, LOCK_EX)) {
			if (fwrite($fp, $data) === FALSE) {
				print("ファイル書き込みに失敗しました");
			}
			flock($fp, LOCK_UN);	
		}
		else {
			print("ファイルロックに失敗しました");
		}
	}
	fclose($fp);
}
?>