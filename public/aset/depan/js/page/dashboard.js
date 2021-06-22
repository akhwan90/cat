(function(d) {
    
    var a=document.getElementById("orders").getContext("2d");
    var b=new Chart(a, {
        type:"roundedBar", data: {
            labels:["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], datasets:[ {
                label: "Delivered", data: [30, 24, 22, 17, 22, 24, 9, 14, 20, 13, 17, 13], borderColor: "#fff", backgroundColor: "#5d5386", hoverBackgroundColor: "#483d77"
            }
            , {
                label: "Estimated", data: [10, 14, 12, 20, 20, 8, 10, 20, 7, 11, 8, 10], borderColor: "#fff", backgroundColor: "#e4e8f0", hoverBackgroundColor: "#dde1e9"
            }
            ]
        }
        , options: {
            responsive:true, barRoundness:1, tooltips: {
                backgroundColor: "rgba(47, 49, 66, 0.8)", titleFontSize: 13, titleFontColor: "#fff", caretSize: 0, cornerRadius: 4, xPadding: 5, displayColors: false, yPadding: 5,
            }
            , legend: {
                display:true, position:"bottom", labels: {
                    fontColor: "#2e3451", usePointStyle: true, padding: 50, fontSize: 13
                }
            }
            , scales: {
                xAxes:[ {
                    barThickness:20, stacked:false, gridLines: {
                        drawBorder: false, display: false
                    }
                    , ticks: {
                        display: true
                    }
                }
                ], yAxes:[ {
                    stacked:false, gridLines: {
                        drawBorder: false, display: false
                    }
                    , ticks: {
                        display: false
                    }
                }
                ]
            }
        }
    }
    );
}

)(jQuery);