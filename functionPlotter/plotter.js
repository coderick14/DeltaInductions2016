      var canvas = document.getElementById('canvas');
      var ctx = canvas.getContext("2d");

      /*startX,startY = x and y coordinates of onmousedown
        originX,originY = origin of the graph(0,0)
        period : interval between numbers displayed on the axes*/
      var zoomFactor = 40,isDragging = false,period=1,startX,startY,originX=canvas.width/2,originY=canvas.height/2;

      document.getElementById('submit').addEventListener("click",draw);
      document.getElementById('zoomin').addEventListener("click",zoomIn);
      document.getElementById('zoomout').addEventListener("click",zoomOut);
      canvas.onmousedown = function(e)  {
        startX = e.clientX - canvas.offsetLeft;
        startY = e.clientY - canvas.offsetTop;
        isDragging = true;
      };
      canvas.onmousemove = function(e)  {
        if(isDragging)  {
          //currentX,currentY = present coordinates of mouse pointer wrt canvas
          var currentX = e.clientX - canvas.offsetLeft;
          var currentY = e.clientY - canvas.offsetTop;
          originX+=(currentX - startX);
          originY+=(currentY - startY);
          startX = currentX;
          startY = currentY;
          draw();
        }
      };
      canvas.onmouseup = function(e)  {
        var stopX = e.clientX - canvas.offsetLeft;
        var stopY = e.clientY - canvas.offsetTop;
        isDragging = false;
        originX+=(stopX - startX);
        originY+=(stopY - startY);
        draw();
      };
      function zoomIn() {
        if(zoomFactor<200) {
          zoomFactor+=10;
          if(zoomFactor<40 && zoomFactor%10==0) {
            period=period/2;
          }
          else if(zoomFactor%40==0)  {
            period=period/2;
          }
          draw();
        }
      }
      function zoomOut()  {
        if(zoomFactor>20) {
          zoomFactor-=5;
          if(zoomFactor<40 && zoomFactor%10==0)  {
            period=2*period;
          }
          else if(zoomFactor%20==0) {
            period=2*period;
          }
          draw();
        }
      }
      function showAxes() {
        ctx.beginPath();
        ctx.strokeStyle = "black";
        ctx.lineWidth = 2;
        ctx.moveTo(0,originY);
        ctx.lineTo(canvas.width,originY);
        ctx.moveTo(originX,canvas.height);
        ctx.lineTo(originX,0);
        ctx.stroke();
      }
      function drawGraph()  {
        var txt = document.getElementById('func').value;
        var fun = "("+txt+")";
        fun = eval(fun);

        var x0 = originX,y0 = originY,scale = zoomFactor,dx = 2,unit = canvas.width/80;
        var xMin = Math.round(-x0/dx);
        var xMax = Math.round((canvas.width-x0)/(dx));
        var rightX = Math.round((canvas.width-originX)/unit);
        var leftX = Math.round(-originX/unit);
        var topY = Math.round(originY/unit);
        var bottomY = Math.round((originY-canvas.height)/unit);
        ctx.beginPath();
        ctx.strokeStyle = "green";
        ctx.lineWidth = 2;
        ctx.font = "Georgia 10px";
        //draw positive x-coordinates
        for(var k=0;k<=rightX;k+=period)  {
          ctx.fillText(k,originX+k*(scale),originY+12);
        }
        //draw negative x-coordinates
        for(var k=-period;k>=leftX;k-=period) {
          ctx.fillText(k,originX+k*(scale),originY+12);
        }
        //draw negative y-coordinates
        for(var k=-period;k>=bottomY;k-=period)  {
          ctx.fillText(k,originX+12,originY+k*(canvas.height/scale - scale));
        }
        //draw positive y-coordinates
        for(var k=0;k<=topY;k+=period)  {
          ctx.fillText(k,originX+12,originY+k*(canvas.height/scale - scale));
        }
        for(var i=xMin;i<xMax;i+=dx)  {
          var x = i*dx,y = scale*fun(x/scale);
          if(i == xMin)  {
            ctx.moveTo(x+x0,y0-y);
          }
          else {
            ctx.lineTo(x+x0,y0-y);
          }
        }
        ctx.stroke();
      }
      //function to draw. Called when the graph is loaded for the first time and on dragging
      function draw() {
        ctx.clearRect(0,0,canvas.width,canvas.height);
        showAxes();
        drawGraph();
      }