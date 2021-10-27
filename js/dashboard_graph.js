const colors = ['#0f371c', '#e5ae48', '#d91b5b', '#1b75bb', '#744c28', '#be1e2d', '#652c90', '#f05a28', '#2e3092', '#404041', '#c981b6','#2db506'];

let gNbsiteDep = new Highcharts.chart("nbSite_Dep", {
    chart: {
        type: 'column',
        backgroundColor: '#f8f9fa',
        },
    title: {
        text: 'Nb Sites / Département'
    },
    xAxis: {categories: ['Département']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Nb Sites'
        }
    },
    credits: {
            enabled: false
        },
    series: [
    {
        name: '14',
        data: [0]
    },{
        name: '27',
        data: [0]
    },{
        name: '50',
        data: [0]
    },{
        name: '61',
        data: [0]
    },{
        name: '76',
        data: [0]
    }]
});



let gNbDDG = new Highcharts.chart("nb_site_ddg", {
            chart: {
                type: 'bar',
                height:180,
                backgroundColor: '#f8f9fa',
                events: {
                    addSeries: function () {
                        setTimeout(function () {}, 1000);
                    }
                }
            },
            title: {
                text: "Sites avec DDG"
                // ,style: {
                //     color: '#d91b5b',
                //     fontSize: '12px',
                //     fontWeight: 'bold'
                //     }
            },
            xAxis: {
                categories: ["ddg"]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'nb sites',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: '',
                shared: true
            },
            plotOptions: {
                bar: {
                    grouping: false,
                    groupPadding:0
                }
            },
            legend: {
                enabled:false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Sites avec DDG',
                color:'#d91b5b',
                data: [],
                pointPlacement: 0
            }, {
                name: 'Sites Total',
                color:'rgba(0,0,0,.2)',
                data: [],
                pointPlacement: 0
            }]
});



let gTypoSite = new Highcharts.chart("graph_typologie_site", {
chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie',
    backgroundColor: '#f8f9fa',
    height:300,
    events: {
        addSeries: function () {
            setTimeout(function () {}, 1000);
        }
    }
},
legend: {
        enabled: false
        // ,
        // layout: 'vertical',
        // floating: false,
        // align: 'right',
        // verticalAlign: 'top',
        // y:30
    },
title: {
    text: 'Typologie des sites'
},
tooltip: {
    pointFormat: '<b>{point.percentage:.1f}%</b>'
},
plotOptions: {
    pie: {
        allowPointSelect: true,
        cursor: 'pointer',
        dataLabels: {
            enabled: false
        },
        showInLegend: false
        // ,colors: colors
    }
},
credits: {
            enabled: false
        },
series: [{
    name: '',
    colorByPoint: true,
    data: [
    {name: 'inconnu',y: 0},
    {name: 'tourbières et marais',y: 0},
    {name: 'milieux variés',y: 0},
    {name: 'milieux rupestres ou rocheux',y: 0},
    {name: 'milieux artificialisés (carrières,…)',y: 0},
    {name: 'sites géologiques',y: 0},
    {name: 'écosystèmes montagnards',y: 0},
    {name: 'autres',y: 0},
    {name: 'pelouses sèches',y: 0},
    {name: 'landes, fruticées et prairies',y: 0},
    {name: 'écosystèmes alluviaux',y: 0},
    {name: 'gîtes à chiroptères',y: 0},
    {name: 'écosystèmes littoraux et marins',y: 0},
    {name: 'écosystèmes aquatiques',y: 0},
    {name: 'écosystèmes forestiers',y: 0},
    {name: 'écosystèmes lacustres',y: 0}
    ]

    // {name: 'inconnu',y: data_[0]},
    // {name: 'tourbières et marais',y: data_[1]},
    // {name: 'milieux variés',y: data_[2]},
    // {name: 'milieux rupestres ou rocheux',y: data_[3]},
    // {name: 'milieux artificialisés (carrières,…)',y: data_[4]},
    // {name: 'sites géologiques',y: data_[5]},
    // {name: 'écosystèmes montagnards',y: data_[6]},
    // {name: 'autres',y: data_[7]},
    // {name: 'pelouses sèches',y: data_[8]},
    // {name: 'landes, fruticées et prairies',y: data_[9]},
    // {name: 'écosystèmes alluviaux',y: data_[10]},
    // {name: 'gîtes à chiroptères',y: data_[11]},
    // {name: 'écosystèmes littoraux et marins',y: data_[12]},
    // {name: 'écosystèmes aquatiques',y: data_[13]},
    // {name: 'écosystèmes forestiers',y: data_[14]},
    // {name: 'écosystèmes lacustres',y: data_[15]}
}]
});



let gSurfaceDep = new Highcharts.chart("surface_Dep", {
    chart: {
        type: 'column',
        backgroundColor: '#f8f9fa'
    },
    title: {
        text: 'Surface / Département'
    },
    xAxis: {categories: ['Département']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Surface Sites'
        }
    },
    credits: {
            enabled: false
        },
    series: [
    {
        name: '14',
        data: [0]
    },{
        name: '27',
        data: [0]
    },{
        name: '50',
        data: [0]
    },{
        name: '61',
        data: [0]
    },{
        name: '76',
        data: [0]
    }
    ]
});
