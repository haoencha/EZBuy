angular.module('signUp',[]).controller('signUpController', function($scope){

    $scope.signup =false;
    $scope.checkbox = 0;

    //$scope.checkbox=function(){
    //    //$scope.checkBox = true;
    //}
    $scope.buttonClick = function(){
        if($scope.checkbox == 1){
            $scope.signup = true;
        }
    }
});