function showHideExp(){
  if(document.getElementById('expType').value=="no numeric treshold"){
      document.getElementById('expLow1').style.display='none';
      document.getElementById('expHigh1').style.display='none';
      document.getElementById('text1').style.display='none';
      document.getElementById('text2').style.display='none';
  } else if(document.getElementById('expType').value=="lower than") {
            document.getElementById('expLow1').style.display='none';
            document.getElementById('expHigh1').style.display='block';
            document.getElementById('text1').style.display='none';
            document.getElementById('text2').style.display='none';
      } else if(document.getElementById('expType').value=="greater than"){
                document.getElementById('expLow1').style.display='block';
                document.getElementById('expHigh1').style.display='none';
                document.getElementById('text1').style.display='none';
                document.getElementById('text2').style.display='none';
      } else if(document.getElementById('expType').value=="between"){
                document.getElementById('expLow1').style.display='block';
                document.getElementById('expHigh1').style.display='block';
                document.getElementById('text1').style.display='none';
                document.getElementById('text2').style.display='none';
      } else {
                  document.getElementById('expLow1').style.display='block';
                  document.getElementById('expHigh1').style.display='block';
                  document.getElementById('text1').style.display='block';
                  document.getElementById('text2').style.display='block';
      }
}

function handleOnchange(){
  if(document.getElementById('icUpper').value< document.getElementById('result').value){
    alert("Upper confint must be higher than result");
            preventDefault();
  }
}


function checkConfint() {
  if (document.getElementById('icUpper').value< document.getElementById('result').value) {
      alert("Check confidence intervals");
      return false;
  }
}