document.addEventListener("DOMContentLoaded", function() {

    var statistics = {
        "averageScore": 150,
        "strikeRate": "30%",
        "spareRate": "40%",
        "highGame": 200,
        "highSeries": 550,
        "firstBallAverage": 8.5,
        "cleanGame": 5,
        "cleanFramePercentage": "50%",
        "scoreDifferential": 10
    };

    document.getElementById("averageScore").innerHTML = statistics.averageScore;
    document.getElementById("strikeRate").innerHTML = statistics.strikeRate;
    document.getElementById("spareRate").innerHTML = statistics.spareRate;
    document.getElementById("highGame").innerHTML = statistics.highGame;
    document.getElementById("highSeries").innerHTML = statistics.highSeries;
    document.getElementById("firstBallAverage").innerHTML = statistics.firstBallAverage;
    document.getElementById("cleanGame").innerHTML = statistics.cleanGame;
    document.getElementById("cleanFramePercentage").innerHTML = statistics.cleanFramePercentage;
    document.getElementById("scoreDifferential").innerHTML = statistics.scoreDifferential;
});