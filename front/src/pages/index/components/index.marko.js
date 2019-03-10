// Compiled using marko@4.15.4 - DO NOT EDIT
"use strict";

var marko_template = module.exports = require("marko/src/html").t(__filename),
    marko_component = {
        onCreate: function() {}
      },
    marko_componentType = "/front$1.0.0/src/pages/index/components/index.marko",
    components_helpers = require("marko/src/components/helpers"),
    marko_renderer = components_helpers.r,
    marko_defineComponent = components_helpers.c;

function render(input, out, __component, component, state) {
  var data = input;

  out.w("<div class=\"mainContainer\"><div class=\"halfContainer\"><span class=\"header-title\"><i class=\"fas fa-clock marr20\"></i> OVERDUE MATCHES</span><div class=\"inContainer90\"></div><span class=\"header-title\"><i class=\"fas fa-clipboard-list marr20\"></i> TOURNAMENT SCHEDULE</span><div class=\"inContainer90\"></div></div><div class=\"halfContainer\"><span class=\"header-title\"><i class=\"fas fa-trophy marr20\"></i> LAST 20 TOURNAMENT RESULTS</span><div class=\"inContainer90\"></div></div><div style=\"clear: both;\"></div></div>");
}

marko_template._ = marko_renderer(render, {
    ___type: marko_componentType
  }, marko_component);

marko_template.Component = marko_defineComponent(marko_component, marko_template._);

marko_template.meta = {
    deps: [
      {
          type: "less",
          code: ".halfContainer {\r\n    float: left;\r\n    width: 50%;\r\n    margin-top: 40px;\r\n  }\r\n\r\n  .inContainer90 {\r\n    margin-top: 20px;\r\n    width: 95%;\r\n  }\r\n\r\n  .mainContainer {\r\n    padding: 0px 20px 0px 20px;\r\n  }\r\n\r\n  .header-title {\r\n    color: white;\r\n    font-size: 30px;\r\n    font-weight: 600;\r\n    margin-bottom: 40px;\r\n    margin-right: 40px;\r\n  }\r\n\r\n  .mainTable {\r\n    width: 100%;\r\n    margin-top: 20px;\r\n  }\r\n\r\n  .greyContainer {\r\n    background: #3e3e3e;\r\n    padding: 20px;\r\n    border-radius: 10px 10px;\r\n    box-shadow: 2px 2px 4px 0px black;\r\n  }\r\n\r\n  .greyContainer .title {\r\n    color: white;\r\n    font-weight: 600;\r\n  }\r\n\r\n  hr {\r\n    border: none;\r\n    border-bottom: 1px solid #b3b3b3;\r\n    height: 1px;\r\n  }\r\n\r\n  .marr20 {\r\n    margin-right: 20px;\r\n  }\r\n\r\n  .playoffBanner {\r\n    margin-top: 40px;\r\n    background: linear-gradient(150deg, #3d9a56 30%, #3e3e3e 60%);\r\n    padding: 20px;\r\n    width: 95%;\r\n    overflow: hidden;\r\n    height: 75px;\r\n  }\r\n\r\n  .insetshadow {\r\n    color: #202020;\r\n    background-color: #2d2d2d;\r\n    letter-spacing: 0.1em;\r\n    text-shadow: -1px -1px 1px #111, 2px 2px 1px #363636;\r\n  }\r\n\r\n  .playoffBanner:hover {\r\n    background-color: #9cbed2;\r\n    cursor: pointer;\r\n  }\r\n\r\n  .bannerImg {\r\n    margin-left: 700px;\r\n    font-size: 150px;\r\n    margin-top: -180px;\r\n  }\r\n\r\n  .stackCircle {\r\n    font-size: 75px;\r\n    color: #ffffff;\r\n  }\r\n\r\n  .stackPaddle {\r\n    color: #459a4c;\r\n    font-size: 50px;\r\n    margin-top: 10px;\r\n  }",
          virtualPath: "./index.marko.less",
          path: "./index.marko"
        }
    ],
    id: "/front$1.0.0/src/pages/index/components/index.marko",
    component: "./"
  };
