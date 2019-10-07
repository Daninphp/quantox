<?php
require_once("dbconn.php");

if(isset($_GET['student'])) {

	$id = $_GET["student"];
	$db = Konekcija::getInstance()->conn;
	$stmt = $db->prepare("SELECT * FROM students where id = $id");
	$stmt->execute();
	$student = $stmt->fetch(PDO::FETCH_ASSOC);


		if($student['board'] == "CSM")
		{
			$array = array($student['maths'], $student['english'], $student['history'], $student['geography']);
			$average = array_sum($array) / count($array);

			if($average>=7)
				$result = "Pass";

			else
				$result = "Fail";

			$report = array( 'student' => $student,
							'average' => $average,
							'result' => $result );

			json_encode($report); ?>

            <h1>Student report</h1>
            <br>
            <p>Student ID: 		<?= $report['student']['id'] ?></p>
            <p>First name: 		<?= $report['student']['name'] ?></p>
            <p>Last  name: 		<?= $report['student']['last_name'] ?> </p>
            <p>English:     	<?= $report['student']['english'] ?></p>
            <p>Maths:       	<?= $report['student']['maths'] ?></p>
            <p>Geography:   	<?= $report['student']['geography'] ?></p>
            <p>History:     	<?= $report['student']['history'] ?></p>
            <p>Average grade: 	<?= $report['average']?></p>
            <p>Final result:    <?= $report['result'] ?></p>

			<?php
		}

		else
		{

			$array = array($student['maths'], $student['english'], $student['history'], $student['geography']);
			$test = array_filter($array);

			sort($test, SORT_NUMERIC);

			if(sizeof($test)>2 )
				array_shift($test);

			$average = array_sum($test) / count($test);



			if(max($test)>8)
			{
				$result = "Pass";
			}
			else
			{
				$result = "Fail";
			}

			$report = array( 'student' => $student,
                            'average' => $average,
                            'result' => $result );

			json_encode($report);

			$xml  = new DOMDocument('1.0', 'UTF-8');
			$xml->formatOutput=true;

			$students = $xml->createElement('students');
			$xml->appendChild($students);

			$student = $xml->createElement('student');
			$students->appendChild($student);

			$head = $xml->createElement('head','Student report');
			$student->appendChild($head);

			$studentId = $xml->createElement('studentId',$report['student']['id']);
			$student->appendChild($studentId);

			$fName = $xml->createElement('FirstName',$report['student']['name']);
			$student->appendChild($fName);

			$lName = $xml->createElement('LastName',$report['student']['last_name']);
			$student->appendChild($lName);

			$english = $xml->createElement('English',$report['student']['english']);
			$student->appendChild($english);

			$maths = $xml->createElement('Maths',$report['student']['maths']);
			$student->appendChild($maths);

			$geo = $xml->createElement('Geography',$report['student']['geography']);
			$student->appendChild($geo);

			$history = $xml->createElement('History',$report['student']['history']);
			$student->appendChild($history);

			$average = $xml->createElement('Average_grade',$report['average']);
			$student->appendChild($average);

			$result = $xml->createElement('Result',$report['result']);
			$student->appendChild($result);

			$xml->appendChild($students);

			$output = $xml->saveXML();
			$header = "Content-Type:text/xml";

			header($header);
			echo $output;
		}
}

