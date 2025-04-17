google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.load('current', {'packages':['line']});
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawBasic);
function drawBasic() {
  if ($("#column-chart1").length > 0) {
      var a = google.visualization.arrayToDataTable([
        ["Year", "Sales", "Expenses", "Profit"],
        ["2018", 1e3, 400, 250],
        ["2019", 1170, 460, 300],
        ["2020", 660, 1120, 400],
        ["2021", 1030, 540, 450]
      ]),
      b = {
        chart: {
          title: "Company Performance",
          subtitle: "Sales, Expenses, and Profit: 2014-2017"
        },
        bars: "vertical",
        vAxis: {
          format: "decimal"
        },
        height: 400,
        width:'100%',
          colors: [CubaAdminConfig.primary, CubaAdminConfig.secondary , "#51bb25"]


      },
    c = new google.charts.Bar(document.getElementById("column-chart1"));
    c.draw(a, google.charts.Bar.convertOptions(b))
  }
  if ($("#column-chart2").length > 0) {
      var a = google.visualization.arrayToDataTable([
        ["Year", "Sales", "Expenses", "Profit"],
        ["2018", 1e3, 400, 250],
        ["2019", 1170, 460, 300],
        ["2020", 660, 1120, 400],
        ["2021", 1030, 540, 450]
      ]),
      b = {
        chart: {
          title: "Company Performance",
          subtitle: "Sales, Expenses, and Profit: 2014-2017"
        },
        bars: "horizontal",
        vAxis: {
          format: "decimal"
        },
        height: 400,
        width:'100%',
        colors: [CubaAdminConfig.primary, CubaAdminConfig.secondary , "#51bb25"]
      },
      c = new google.charts.Bar(document.getElementById("column-chart2"));
      c.draw(a, google.charts.Bar.convertOptions(b))
  }
  if ($("#pie-chart1").length > 0) {
    // Chargez l'API Google Charts
    google.charts.load('current', { 'packages': ['corechart'] });

    // Attendez que Google Charts soit chargé avant d'exécuter le reste
    google.charts.setOnLoadCallback(drawChart);
      // Fonction pour dessiner le graphique
    function drawChart() {
      // Utilisez AJAX pour obtenir les données du serveur
      $.ajax({
        url: '/getData', // URL de l'endpoint qui renvoie les données
        method: 'GET',
        success: function (data) {
          // Une fois les données reçues, construisez le tableau de données pour Google Charts
          var chartData = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Auto', data.auto],
            ['Voyage', data.voyage],  // Utilisez les données reçues
                   // Utilisez les données reçues
          ]);

          // Options du graphique
          var options = {
            title: 'Mes ventes',
            width: '100%',
            height: 300,
            colors: [CubaAdminConfig.primary,"#5c1245"] // Personnalisez les couleurs
          };

          // Créez et dessinez le graphique
          var chart = new google.visualization.PieChart(document.getElementById('pie-chart1'));
          chart.draw(chartData, options);
           // Ajoutez un événement de sélection pour rendre les parties du graphique cliquables
          google.visualization.events.addListener(chart, 'select', function () {
            var selectedItem = chart.getSelection()[0]; // Obtenez l'élément sélectionné
            if (selectedItem) {
                var task = chartData.getValue(selectedItem.row, 0); // Obtenez le label ("Voyage" ou "Auto")

                // Redirigez en fonction du label
                if (task === 'Voyage') {
                    window.location.href = '/home#pills-warningprosous1'; // URL pour la section "Voyage"
                } else if (task === 'Auto') {
                    window.location.href = '/home#pills-warningprofile1'; // URL pour la section "Auto"
                }
            }
        });
        },
        error: function (xhr, status, error) {
          console.error('Erreur lors de la récupération des données :', error);
        }
      });
    }
  }
  if ($("#pie-chart2").length > 0) {
      // Chargez l'API Google Charts
    google.charts.load('current', { 'packages': ['corechart'] });

    // Attendez que Google Charts soit chargé avant d'exécuter le reste
    google.charts.setOnLoadCallback(drawChart);
      // Fonction pour dessiner le graphique
    function drawChart() {
      // Utilisez AJAX pour obtenir les données du serveur
      $.ajax({
        url: '/getDataPay', // URL de l'endpoint qui renvoie les données
        method: 'GET',
        success: function (data) {
          // Une fois les données reçues, construisez le tableau de données pour Google Charts
          var chartData = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Wave', data.wave],  // Utilisez les données reçues
            ['Orange Money', data.om]       // Utilisez les données reçues
          ]);

          // Options du graphique
          var options = {
            title: 'Mode de paiement',
            width: '100%',
            height: 300,
            colors: ["#1ab3e5","#f16e00"] // Personnalisez les couleurs
          };

          // Créez et dessinez le graphique
          var chart = new google.visualization.PieChart(document.getElementById('pie-chart2'));
          chart.draw(chartData, options);
        },
        error: function (xhr, status, error) {
          console.error('Erreur lors de la récupération des données :', error);
        }
      });
    }
  }
  if ($("#pie-chart3").length > 0) {
       // Chargez l'API Google Charts
    google.charts.load('current', { 'packages': ['corechart'] });

    // Attendez que Google Charts soit chargé avant d'exécuter le reste
    google.charts.setOnLoadCallback(drawChart);
      // Fonction pour dessiner le graphique
    function drawChart() {
      // Utilisez AJAX pour obtenir les données du serveur
      $.ajax({
        url: '/getData', // URL de l'endpoint qui renvoie les données
        method: 'GET',
        success: function (data) {
          // Une fois les données reçues, construisez le tableau de données pour Google Charts
          var chartData = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['simulation auto', data.sim_auto],  // Utilisez les données reçues
            ['simulation voyage', data.sim_voyage]       // Utilisez les données reçues
          ]);

          // Options du graphique
          var options = {
            title: 'Les simulations',
            width: '100%',
            height: 300,
            colors: [CubaAdminConfig.secondary,"#008ffb"] // Personnalisez les couleurs
          };

          // Créez et dessinez le graphique
          var chart = new google.visualization.PieChart(document.getElementById('pie-chart3'));
          chart.draw(chartData, options);
        },
        error: function (xhr, status, error) {
          console.error('Erreur lors de la récupération des données :', error);
        }
      });
    }
  }
  if ($("#pie-chart4").length > 0) {
         // Chargez l'API Google Charts
    google.charts.load('current', { 'packages': ['corechart'] });

    // Attendez que Google Charts soit chargé avant d'exécuter le reste
    google.charts.setOnLoadCallback(drawChart);
      // Fonction pour dessiner le graphique
    function drawChart() {
      // Utilisez AJAX pour obtenir les données du serveur
      $.ajax({
        url: '/getData', // URL de l'endpoint qui renvoie les données
        method: 'GET',
        
        success: function (data) {
          console.log('Réponse du serveur :', data);

          // Une fois les données reçues, construisez le tableau de données pour Google Charts
          var chartData = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['simulation auto', data.sim_auto],  // Utilisez les données reçues
            ['simulation voyage', data.sim_voyage],
            ['Auto', data.auto],
            ['Voyage', data.voyage] ,      // Utilisez les données reçues
          ]);

          // Options du graphique
          var options = {
            title: 'Les simulations et ventes',
            width: '100%',
            height: 300,
            colors: [CubaAdminConfig.secondary,"#008ffb",CubaAdminConfig.primary,"#5c1245"] // Personnalisez les couleurs
          };

          // Créez et dessinez le graphique
          var chart = new google.visualization.PieChart(document.getElementById('pie-chart4'));
          chart.draw(chartData, options);
        },
        error: function (xhr, status, error) {
          console.error('Erreur lors de la récupération des données :', error);
        }
      });
    }
  }
  if ($("#line-chart").length > 0) {
      var data = new google.visualization.DataTable();
      data.addColumn('number', 'month');
      data.addColumn('number', 'Guardians of the Galaxy');
      data.addColumn('number', 'The Avengers');
      data.addColumn('number', 'Transformers: Age of Extinction');
      data.addRows([
        [1,  37.8, 80.8, 41.8],
        [2,  30.9, 10.5, 32.4],
        [3,  40.4,   57, 25.7],
        [4,  11.7, 18.8, 10.5],
        [5,  20, 17.6, 10.4],
        [6,   8.8, 13.6,  7.7],
        [7,   7.6, 12.3,  9.6],
        [8,  12.3, 29.2, 10.6],
        [9,  16.9, 42.9, 14.8],
        [10, 12.8, 30.9, 11.6],
        [11,  5.3,  7.9,  4.7],
        [12,  6.6,  8.4,  5.2],
      ]);
      var options = {
        chart: {
          title: 'Box Office Earnings in First Two Weeks of Opening',
          subtitle: 'in millions of dollars (USD)'
        },
        colors: [CubaAdminConfig.primary , CubaAdminConfig.secondary , "#51bb25"],
        height: 500,
        width:'100%',
      };
      var chart = new google.charts.Line(document.getElementById('line-chart'));
      chart.draw(data, google.charts.Line.convertOptions(options));
  }
  if ($("#combo-chart").length > 0) {
      var data = google.visualization.arrayToDataTable([
        ['Month', 'Bolivia', 'Ecuador', 'Madagascar', 'Papua', 'Rwanda', 'Average'],
        ['2004/05',  165,      938,         522,             998,           450,      614.6],
        ['2005/06',  135,      1120,        599,             1268,          288,      682],
        ['2006/07',  157,      1167,        587,             807,           397,      623],
        ['2007/08',  139,      1110,        615,             968,           215,      609.4],
        ['2008/09',  136,      691,         629,             1026,          366,      569.6]
      ]);
      var options = {
        title : 'Monthly Coffee Production by Country',
        vAxis: {title: 'Cups'},
        hAxis: {title: 'Month'},
        seriesType: 'bars',
        series: {5: {type: 'line'}},
        height: 500,
        width:'100%',
        colors: [CubaAdminConfig.primary, CubaAdminConfig.secondary , "#51bb25", "#a927f9", "#f8d62b"]
    };
    var chart = new google.visualization.ComboChart(document.getElementById('combo-chart'));
    chart.draw(data, options);
  }
  if ($("#area-chart1").length > 0) {
      var data = google.visualization.arrayToDataTable([
        ['Year', 'Sales', 'Expenses'],
        ['2013',  1000,      400],
        ['2014',  1170,      460],
        ['2015',  660,       1120],
        ['2016',  1030,      540]
      ]);
      var options = {
        title: 'Company Performance',
        hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
        vAxis: {minValue: 0},
        width:'100%',
        height: 400,
        colors: [ CubaAdminConfig.primary , CubaAdminConfig.secondary ]
      };
      var chart = new google.visualization.AreaChart(document.getElementById('area-chart1'));
      chart.draw(data, options);
  }
  if ($("#area-chart2").length > 0) {
    var data = google.visualization.arrayToDataTable([
      ['Year', 'Cars', 'Trucks' , 'Drones' , 'Segways'],
      ['2013',  100, 400, 2000, 400],
      ['2014',  500, 700, 530, 800],
      ['2015',  2000, 1000, 620, 120],
      ['2016',  120, 201, 2501, 540]
    ]);
    var options = {
      title: 'Company Performance',
      hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
      vAxis: {minValue: 0},
      width:'100%',
      height: 400,
      colors: [CubaAdminConfig.primary , CubaAdminConfig.secondary , "#51bb25", "#f8d62b"]
    };
    var chart = new google.visualization.AreaChart(document.getElementById('area-chart2'));
    chart.draw(data, options);
  }
    if ($("#bar-chart2").length > 0) {
        var a = google.visualization.arrayToDataTable([
                ["Element", "Density", {
                    role: "style"
                }],
                ["Copper", 10, "#a927f9"],
                ["Silver", 12, "#f8d62b"],
                ["Gold", 14, "#f73164"],
                ["Platinum", 16, "color: #7366ff"]
            ]),
            d = new google.visualization.DataView(a);
        d.setColumns([0, 1, {
            calc: "stringify",
            sourceColumn: 1,
            type: "string",
            role: "annotation"
        }, 2]);
        var b = {
                title: "Density of Precious Metals, in g/cm^3",
                width:'100%',
                height: 400,
                bar: {
                    groupWidth: "95%"
                },
                legend: {
                    position: "none"
                }
            },
            c = new google.visualization.BarChart(document.getElementById("bar-chart2"));
        c.draw(d, b)
    }
}
// Gantt chart
google.charts.load('current', {'packages':['gantt']});
google.charts.setOnLoadCallback(drawChart);

function daysToMilliseconds(days) {
    return days * 24 * 60 * 60 * 1000;
}

function drawChart() {

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Task ID');
    data.addColumn('string', 'Task Name');
    data.addColumn('string', 'Resource');
    data.addColumn('date', 'Start Date');
    data.addColumn('date', 'End Date');
    data.addColumn('number', 'Duration');
    data.addColumn('number', 'Percent Complete');
    data.addColumn('string', 'Dependencies');

    data.addRows([
        ['Research', 'Find sources', null,
            new Date(2015, 0, 1), new Date(2015, 0, 5), null,  100,  null],
        ['Write', 'Write paper', 'write',
            null, new Date(2015, 0, 9), daysToMilliseconds(3), 25, 'Research,Outline'],
        ['Cite', 'Create bibliography', 'write',
            null, new Date(2015, 0, 7), daysToMilliseconds(1), 20, 'Research'],
        ['Complete', 'Hand in paper', 'complete',
            null, new Date(2015, 0, 10), daysToMilliseconds(1), 0, 'Cite,Write'],
        ['Outline', 'Outline paper', 'write',
            null, new Date(2015, 0, 6), daysToMilliseconds(1), 100, 'Research']
    ]);

    var options = {
        height: 275,
        gantt: {
            criticalPathEnabled: false, // Critical path arrows will be the same as other arrows.
            arrow: {
                angle: 100,
                width: 5,
                color: '#51bb25',
                radius: 0
            },

                palette: [
                    {
                        "color": CubaAdminConfig.primary,
                        "dark": CubaAdminConfig.secondary ,
                        "light": "#047afb"
                    }
                ]

        }
    };
    var chart = new google.visualization.Gantt(document.getElementById('gantt_chart'));

    chart.draw(data, options);
}
// word tree
google.charts.load('current1', {packages:['wordtree']});
google.charts.setOnLoadCallback(drawChart1);

function drawChart1() {
    var data = google.visualization.arrayToDataTable(
        [ ['Phrases'],
            ['cats are better than dogs'],
            ['cats eat kibble'],
            ['cats are better than hamsters'],
            ['cats are awesome'],
            ['cats are people too'],
            ['cats eat mice'],
            ['cats meowing'],
            ['cats in the cradle'],
            ['cats eat mice'],
            ['cats in the cradle lyrics'],
            ['cats eat kibble'],
            ['cats for adoption'],
            ['cats are family'],
            ['cats eat mice'],
            ['cats are better than kittens'],
            ['cats are evil'],
            ['cats are weird'],
            ['cats eat mice']
        ]
    );

    var options = {
        wordtree: {
            format: 'implicit',
            word: 'cats'
        }

    };
    var chart = new google.visualization.WordTree(document.getElementById('wordtree_basic'));
    chart.draw(data, options);
}
