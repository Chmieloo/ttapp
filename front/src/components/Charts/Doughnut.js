import { Doughnut } from "vue-chartjs";

export default {
  extends: Doughnut,
  props: {
    chartData: {
      type: Array | Object,
      required: false,
    },
  },
  draw: function () {
    this.types.Doughnut.prototype.draw.apply(this, arguments);

    var width = this.chart.width;
    var height = this.chart.height;

    var fontSize = (height / 114).toFixed(2);
    this.chart.ctx.font = fontSize + "em Lato";
    this.chart.ctx.textBaseline = "middle";

    var text = "40%";
    var textX = Math.round(
      (width - this.chart.ctx.measureText(text).width) / 2
    );
    var textY = height / 2;

    this.chart.ctx.fillText(text, textX, textY);
  },
  mounted() {
    this.renderChart(
      {
        labels: this.chartLabels,
        options: {
          percentageInnerCutout: 10,
        },
        datasets: [
          {
            label: "downloads",
            borderColor: "#249EBF",
            pointBackgroundColor: "white",
            borderWidth: 1,
            pointBorderColor: "#249EBF",
            backgroundColor: "white",
            data: this.chartData,
          },
        ],
      },
      this.options
    );
  },
};
