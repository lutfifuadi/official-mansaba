/**
 * Dashboard Operator - MAN 1 Kota Bandung
 */

'use strict';

(function () {
  let cardColor, labelColor, headingColor;
  cardColor = config.colors.cardColor;
  labelColor = config.colors.textMuted;
  headingColor = config.colors.headingColor;

  // Total Berita Sparkline
  const totalBeritaSparkEl = document.querySelector('#totalBeritaSpark');
  if (totalBeritaSparkEl) {
    const totalBeritaConfig = {
      chart: {
        height: 75,
        type: 'area',
        parentHeightOffset: 0,
        toolbar: { show: false },
        sparkline: { enabled: true }
      },
      markers: {
        colors: 'transparent',
        strokeColors: 'transparent'
      },
      grid: { show: false },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.4,
          gradientToColors: [config.colors.cardColor],
          opacityTo: 0.1,
          stops: [0, 100]
        }
      },
      dataLabels: { enabled: false },
      stroke: { width: 2, curve: 'smooth' },
      series: [{ data: [200, 120, 300, 240] }],
      xaxis: { labels: { show: false }, axisBorder: { show: false }, axisTicks: { show: false } },
      yaxis: { show: false },
      tooltip: { enabled: false }
    };
    new ApexCharts(totalBeritaSparkEl, totalBeritaConfig).render();
  }

  // Berita Published Sparkline
  const beritaPublishedSparkEl = document.querySelector('#beritaPublishedSpark');
  if (beritaPublishedSparkEl) {
    const beritaPublishedConfig = {
      chart: {
        height: 75,
        type: 'area',
        parentHeightOffset: 0,
        toolbar: { show: false },
        sparkline: { enabled: true }
      },
      markers: {
        colors: 'transparent',
        strokeColors: 'transparent'
      },
      grid: { show: false },
      colors: [config.colors.success],
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.4,
          gradientToColors: [config.colors.cardColor],
          opacityTo: 0.1,
          stops: [0, 100]
        }
      },
      dataLabels: { enabled: false },
      stroke: { width: 2, curve: 'smooth' },
      series: [{ data: [55, 80, 230, 180] }],
      xaxis: { labels: { show: false }, axisBorder: { show: false }, axisTicks: { show: false } },
      yaxis: { show: false },
      tooltip: { enabled: false }
    };
    new ApexCharts(beritaPublishedSparkEl, beritaPublishedConfig).render();
  }

  // Weekly Published Bar Chart
  const weeklyChartEl = document.querySelector('#weeklyPublishedChart');
  if (weeklyChartEl && window.__operatorDashboard) {
    const d = window.__operatorDashboard;

    const weeklyConfig = {
      chart: {
        height: 162,
        type: 'bar',
        parentHeightOffset: 0,
        toolbar: { show: false }
      },
      plotOptions: {
        bar: {
          barHeight: '80%',
          columnWidth: '30%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 5,
          distributed: true
        }
      },
      tooltip: { enabled: false },
      grid: {
        show: false,
        padding: { top: -20, bottom: -12, left: -13, right: -3 }
      },
      colors: [
        config.colors_label.primary,
        config.colors_label.primary,
        config.colors_label.primary,
        config.colors_label.primary,
        config.colors.primary,
        config.colors_label.primary,
        config.colors_label.primary
      ],
      dataLabels: { enabled: false },
      series: [{ data: d.weeklyData }],
      legend: { show: false },
      xaxis: {
        categories: d.weeklyLabels,
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px',
            fontFamily: config.fontFamily
          }
        }
      },
      yaxis: { labels: { show: false } },
      states: {
        hover: { filter: { type: 'none' } }
      },
      responsive: [
        { breakpoint: 1471, options: { plotOptions: { bar: { columnWidth: '45%' } } } },
        { breakpoint: 1350, options: { plotOptions: { bar: { columnWidth: '57%' } } } },
        { breakpoint: 1032, options: { plotOptions: { bar: { columnWidth: '60%' } } } },
        { breakpoint: 992, options: { plotOptions: { bar: { columnWidth: '40%', borderRadius: 8 } } } },
        { breakpoint: 855, options: { plotOptions: { bar: { columnWidth: '50%', borderRadius: 6 } } } },
        { breakpoint: 440, options: { plotOptions: { bar: { columnWidth: '40%' } } } },
        { breakpoint: 381, options: { plotOptions: { bar: { columnWidth: '45%' } } } }
      ]
    };
    new ApexCharts(weeklyChartEl, weeklyConfig).render();
  }
})();
