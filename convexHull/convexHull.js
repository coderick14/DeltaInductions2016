      var points = [];
      var canvas = document.getElementById('canvas');
      var ctx = canvas.getContext("2d");
      var offsetX = canvas.offsetLeft;
      var offsetY = canvas.offsetTop;
      document.getElementById('submit').addEventListener("click",drawHull);

      canvas.onmousedown = function(e)  {
        var xx = e.clientX - offsetX;
        var yy = e.clientY - offsetY;
        points.push({x:xx,y:yy});
        ctx.beginPath();
        ctx.fillStyle = "yellow";
        ctx.arc(xx,yy,3,0,2*Math.PI);
        ctx.fill();
      }

      //use Jarvis' Algorithm to draw the convex hull
      function drawHull() {
        var leftMost = 0,len = points.length;
        var hull = [];
        for(var i=1;i<len;i++)  {
          if(points[i].x<points[leftMost].x)  {
            leftMost = i;
          }
        }

        var p = leftMost;

        do {

          hull.push({x:points[p].x,y:points[p].y});
          var q = (p+1)%len;
          for(var r=0;r<len;r++)  {

            var product = (points[r].x - points[p].x)*(points[q].y - points[r].y) - (points[q].x - points[r].x)*(points[r].y - points[p].y);
            if(product>0) {
              q = r;
            }
          }
          p = q;
        } while (p!=leftMost);
        ctx.beginPath();
        ctx.lineWidth = 2;
        ctx.strokeStyle = "lightgreen";
        ctx.moveTo(hull[0].x,hull[0].y);
        for(var i=1;i<hull.length;i++)  {
          ctx.lineTo(hull[i].x,hull[i].y);
        }
        ctx.closePath();
        ctx.stroke();
      }
