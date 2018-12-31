<?php
	//My second attempt at designing a basic nerual network. It is based on data from
	//this video: https://www.youtube.com/watch?v=gQLKufQ35VE.
	
	//The neural network takes in 2 arguments (length and width) and returns how
	//much it thinks the flower is either blue or red NOW WITHIN TWO ARGUMENTS.
	
	//Program created by Kanishk Gupta on December 27, 2018 at 7:25 PM.

	//SETUP----
	//array(length,width,red, blue)
	$trainingData = array(
			array(3,2,1,0),
			array(2.5,1.5,1,0),
			array(2,2.5,1,0),
			array(1.75,1,1,0),
			array(3,1.5,0,1),
			array(3.5,.5,0,1),
			array(4,1.5,0,1),
			array(5.5,1,0,1)
	);
	
	//array(length,width)
	$testingData = array(
		array(4,1.5)	
	);
	
	$weights = array(
        array(rand(-200,200)*.001,rand(-200,200)*.001),
        array(rand(-200,200)*.001,rand(-200,200)*.001)
    );
	$biasRed = rand(-200,200)*.001;
    $biasBlue = rand(-200,200)*.001;
	
	$learningRate = .05;
    $i = 1;
    $costSum = 0;
    $ctsCostSum = 0;
    $costAvg = 1;

	function sigmoid($val){
		return 1/(1+exp(-$val));
	}
	
	//TRAINING----
	while($costAvg > 0.005){
		//Finding current cost of the network
		$randIndex = rand(0,count($trainingData)-1);//Choose Random Data Set
        $point = $trainingData[$randIndex];
        
		$red = $weights[0][0]*$point[0] + $weights[1][0]*$point[1] + $biasRed;//Red Prediction
		$predRed = sigmoid($red);
        
        $blue = $weights[0][1]*$point[0] + $weights[1][1]*$point[1] + $biasBlue;//Blue Prediction
        $predBlue = sigmoid($blue);
        
		$targetRed = $point[2];//Target Values
        $targetBlue = $point[3];
        
        $costRed = pow(($predRed - $targetRed),2);//Costs
        $costBlue = pow(($predBlue - $targetBlue),2);
		$cost = .5*$costRed + .5*$costBlue;
        
        $costSum += $cost;
        $ctsCostSum += $cost;
        echo("Cost: " . $cost . "; Continuous Cost Average: " . $ctsCostSum/$i . "<br/>");
        
		//Partial Derivatives
		$dcost_dpredR = ($predRed - $targetRed);
		$dpredR_dRed = sigmoid($red) * (1-sigmoid($red));
		$dcost_dRed = $dcost_dpredR*$dpredR_dRed;
        
        $dcost_dpredB = ($predBlue - $targetBlue);
		$dpredB_dBlue = sigmoid($blue) * (1-sigmoid($blue));
		$dcost_dBlue = $dcost_dpredB*$dpredB_dBlue;
		
		$dRed_dw00 = $point[0];
		$dRed_dw10 = $point[1];
        $dRed_dbRed = 1;
        
        $dBlue_dw01 = $point[0];
        $dBlue_dw11 = $point[1];
		$dBlue_dbBlue = 1;
		
		//Full Derivatives
		$dcost_dw00 = $dcost_dRed*$dRed_dw00;
        $dcost_dw10 = $dcost_dRed*$dRed_dw10;
		$dcost_dw01 = $dcost_dBlue*$dBlue_dw01;
        $dcost_dw11 = $dcost_dBlue*$dBlue_dw11;
		$dcost_dbRed = $dcost_dRed*$dRed_dbRed;
        $dcost_dbBlue = $dcost_dBlue*$dBlue_dbBlue;
        
        //Weight and Bias Adjustments
		$weights[0][0] -= ($learningRate*$dcost_dw00);
        $weights[1][0] -= ($learningRate*$dcost_dw10);
        $weights[0][1] -= ($learningRate*$dcost_dw01);
		$weights[1][1] -= ($learningRate*$dcost_dw11);
		$biasRed -= ($learningRate*$dcost_dbRed);
        $biasBlue -= ($learningRate*$dcost_dbBlue);
        
        //Return Cost Periodically
        if($i % 1000 == 0){
            $costAvg = $costSum/1000;
            echo("<strong>Cost Average: " . $costAvg . "</strong><br/>");
            $costSum = 0;
        }
        
        $i += 1;
	}

    echo("Iterations: " . $i . "<br/>");
    echo("Weight[0,0]: " . $weights[0][0] . "; Weight[1,0]: " . $weights[1][0] . "; Weight[0,1]: " . $weights[0][1] . "; Weight[1,1]: " . $weights[1][1] .                 "; Red Bias: " . $biasRed . "; Blue Bias: " . $biasBlue . "<br/><br/>");
    //TESTING
    echo("Likely Red: " . 100*sigmoid($weights[0][0]*$point[0] + $weights[1][0]*$point[1] + $biasRed) . "% <br/>");
    echo("Likely Blue: " . 100*sigmoid($weights[0][1]*$point[0] + $weights[1][1]*$point[1] + $biasBlue) . "% <br/>");
?>