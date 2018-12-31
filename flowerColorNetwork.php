<?php
	//My first attempt at designing a basic nerual network. It is based on data from
	//this video: https://www.youtube.com/watch?v=gQLKufQ35VE.
	
	//The neural network takes in 2 arguments (length and width) and returns how
	//much it thinks the flower is either blue or red.
	
	//Program created by Kanishk Gupta on December 27, 2018 at 5:10 PM.

	//SETUP----
	//array(length,width,color (0->blue,1->red))
	$trainingData = array(
			array(3,2,0),
			array(2.5,1.5,0),
			array(2,2.5,0),
			array(1.75,1,0),
			array(3,1.5,1),
			array(3.5,.5,1),
			array(4,1.5,1),
			array(5.5,1,1)
	);
	
	//array(length,width)
	$testingData = array(
		array(4.5,1)	
	);
	
	$weights = array(rand(-200,200)*.001,rand(-200,200)*.001);
	$bias = rand(-200,200)*.001;
	
	$learningRate = .01;
	
	function sigmoid($val){
		return 1/(1+exp(-$val));
	}
	
	//TRAINING----
	for($i = 0; $i < 200000; $i++){
		//Finding current cost of the network
		$randIndex = rand(0,count($trainingData)-1);
        $point = $trainingData[$randIndex];
        
		$z = $weights[0]*$point[0] + $weights[1]*$point[1] + $bias;
		$pred = sigmoid($z);
        
		$target = $point[2];
		$cost = pow(($pred - $target),2);
        
		//Partial Derivatives
		$dcost_dpred = 2*($pred - $target);
		$dpred_dz = sigmoid($z) * (1-sigmoid($z));
		$dcost_dz = $dcost_dpred*$dpred_dz;
		
		$dz_dw0 = $point[0];//Can make into an array also
		$dz_dw1 = $point[1];
		$dz_db = 1;
		
		//Full Derivatives
        if(($i % 5000) == 0){
			echo(" Cost: " . $cost . 
				"; Prediction:" . $pred . "<br/>");
		}
        
		$dcost_dw0 = $dcost_dz*$dz_dw0;
		$dcost_dw1 = $dcost_dz*$dz_dw1;
		$dcost_db = $dcost_dz*$dz_db;
		
		$weights[0] -= ($learningRate*$dcost_dw0);
		$weights[1] -= ($learningRate*$dcost_dw1);
		$bias -= ($learningRate*$dcost_db);
	}

    //TESTING
    echo(sigmoid($weights[0]*$testingData[0][0] + $weights[1]*$testingData[0][1] + $bias));
?>