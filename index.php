<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="
        https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
        " rel="stylesheet"
    >
  </head>
  <body>
    <!-- header -->
    <div class="flex items-center justify-between px-20 py-4">
      <h1 class="text-2xl font-bold text-red-500">Usbibracelet</h1>
      <form class="relative flex w-3/5 items-center" action="./include/search-product.inc.php" method="post">
        <input
          class="w-full rounded-xl border bg-[#FFEAEA] p-2"
          placeholder="Tìm kiếm ..."
          name="searchTerm"
        />
        <button class="absolute right-3 h-6">
          <img src="./assets/images/search.png" class="h-full w-auto" />
        </button>
      </form>
      <div class="flex items-center gap-4">
        <?php
          if (isset($_SESSION["user_name"])) {
            echo "<a href='cart.php'><button class='rounded-lg border bg-green-400 px-6 py-2 font-bold'>Cart</button></a>";
            echo "<form method='post' action='include/logout.inc.php'><button class='rounded-lg border bg-green-400 px-6 py-2 font-bold'>Log Out</button></form>";
          } else {
            echo "<button id='btn_login' class='rounded-lg border bg-blue-400 px-6 py-2 font-bold'>Login</button>";
            echo "<button class='rounded-lg border bg-green-400 px-6 py-2 font-bold'>Register</button>";
          }
        ?>
      </div>
    </div>
    <div class="bg-[#FFEAEA]">
      <ul
        class="mt-2 flex items-center justify-around text-2xl font-bold text-[#CE112D] relative"
      >
        <li><a href="index.php">Trang chủ</a></li>
        <li>Bài viết</li>
        <li class="group relative py-4">
          <a href="product-list.php" class="menu-hover">Cửa hàng</a>
          <div class="text-lg font-semibold w-60 px-3 invisible  translate-y-[16px] -translate-x-10 bg-slate-200 absolute z-50 flex flex-col py-0 min-w-48 text-gray-800 shadow-xl group-hover:visible">
            <a>Charm Cho Nam</a>
            <a>Charm Cho Nữ</a>
            <a>Charm Cho Nam</a>
            <a>Charm Cho Nam</a>
          </div>
        </li>
        <li>Về chúng tôi</li>
        <li>Tin tức</li>
      </ul>
    </div>
    <!-- end header -->



    </div>
    <div class="splide mt-10 mx-auto h-[520px] w-4/5" role="group" aria-label="Splide Basic HTML Example">
        <div class="splide__track w-full h-full">
            <ul class="splide__list w-full h-full">
                <li class="splide__slide">
                    <img
                        class="h-full w-full object-fill"
                        src="https://static.vecteezy.com/system/resources/previews/022/460/209/non_2x/a-computer-desktop-wallpaper-for-forex-trading-terminal-ai-generative-desktop-background-free-photo.jpg"
                    />
                </li>
                <li class="splide__slide">
                    <img
                        class="h-full w-full object-fill"
                        src="https://static.vecteezy.com/system/resources/previews/022/460/209/non_2x/a-computer-desktop-wallpaper-for-forex-trading-terminal-ai-generative-desktop-background-free-photo.jpg"
                    />
                </li>
                <li class="splide__slide">
                    <img
                        class="h-full w-full object-fill"
                        src="https://static.vecteezy.com/system/resources/previews/022/460/209/non_2x/a-computer-desktop-wallpaper-for-forex-trading-terminal-ai-generative-desktop-background-free-photo.jpg"
                    />
                </li>
            </ul>
        </div>
    </div>

    <div class="mx-auto my-10 w-3/5 border-2"></div>

    <!-- product grid -->
    

    <div class="w-4/5 mx-auto">
      <div class="flex gap-5">
        <div class="h-10 w-5 bg-red-500"></div>
        <h1 class="font-bold text-3xl">Categories</h1>
      </div>
      <h1 class="mt-5 text-red-500 font-semibold text-3xl">BROWSE BY CATEGORY</h1>
      <div class="mt-8 grid w-full grid-cols-7 gap-8">
        <div class="flex flex-col items-center">
          <div class="h-25 w-25">
            <img
              class="h-full w-full"
              src="data:image/jpeg;base64,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tYkPuZ1O5scFWbk+ZHpOr2yXd7T/AAatUHJVxUpMoRDyTudQc5AwMnOeOORRpNNtzaWFOnUbcVUAt6kDmSYiQJgNc1Q6r2jWiRutlcU/ZGN+0VS7sei4G0Y5yffN/PMl0/8AlbU6SGjUfeHL4JqMqtTpMGH82NpyvB3Op9YGZs3qntHUubimUS4Pdh0Qpb11pHcz/WMWDk06eOg275q3qfhmj3FHuwyjaK7JvdCQR4eABtBDgk4PAwQ2ZUWlyur6nptKhVzsoik9LDkI6Iylxk93knb05x18poV7LXNvehbNaaUgB4g3nk58AA49P4QNPoFu9iHpVKhqouChPVQRyvUnA4I/Sx5SyFugqbgq59cDP2znYWv0WgAx3HzP+/8AfMkwEREBERAREQEREBERAREQEREBERAREQI97Y0r+kFvqaVVByFdQ4BHQ4YdeT9s/K9ilSwNGmBTUjA2gAL6YHTg8yTECo0nSGtbjvLxw742jaGA958bMc+gzgZPXJMt4iAiIgJntb7K0tQ1EXNuBTuAAu/nxKrKwBAIz7PnnGTNDEDG9m+x34K1jvXICICKagAHlnbxkHDFd5VTgYUAc5M2URAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQP//Z"
            />
          </div>
          <p class="mt-2 text-lg text-center font-bold">Charm cho nữ</p>
        </div>
        <div class="flex flex-col items-center">
          <div class="h-25 w-25">
            <img class="h-full w-full" src="./assets/images/charmNam.jpg" />
          </div>
          <p class="mt-2 text-lg text-center font-bold">Charm cho nam</p>
        </div>
        <div class="flex flex-col items-center">
          <div class="h-25 w-25">
            <img
              class="h-full w-full"
              src="data:image/jpeg;base64,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tYkPuZ1O5scFWbk+ZHpOr2yXd7T/AAatUHJVxUpMoRDyTudQc5AwMnOeOORRpNNtzaWFOnUbcVUAt6kDmSYiQJgNc1Q6r2jWiRutlcU/ZGN+0VS7sei4G0Y5yffN/PMl0/8AlbU6SGjUfeHL4JqMqtTpMGH82NpyvB3Op9YGZs3qntHUubimUS4Pdh0Qpb11pHcz/WMWDk06eOg275q3qfhmj3FHuwyjaK7JvdCQR4eABtBDgk4PAwQ2ZUWlyur6nptKhVzsoik9LDkI6Iylxk93knb05x18poV7LXNvehbNaaUgB4g3nk58AA49P4QNPoFu9iHpVKhqouChPVQRyvUnA4I/Sx5SyFugqbgq59cDP2znYWv0WgAx3HzP+/8AfMkwEREBERAREQEREBERAREQEREBERAREQI97Y0r+kFvqaVVByFdQ4BHQ4YdeT9s/K9ilSwNGmBTUjA2gAL6YHTg8yTECo0nSGtbjvLxw742jaGA958bMc+gzgZPXJMt4iAiIgJntb7K0tQ1EXNuBTuAAu/nxKrKwBAIz7PnnGTNDEDG9m+x34K1jvXICICKagAHlnbxkHDFd5VTgYUAc5M2URAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQP//Z"
            />
          </div>
          <p class="mt-2 text-lg text-center font-bold">Charm cho nữ</p>
        </div>
        <div class="flex flex-col items-center">
          <div class="h-25 w-25">
            <img class="h-full w-full" src="./assets/images/charmNam.jpg" />
          </div>
          <p class="mt-2 text-lg text-center font-bold">Charm cho nam</p>
        </div><div class="flex flex-col items-center">
          <div class="h-25 w-25">
            <img
              class="h-full w-full"
              src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMHBhISBxMWERMVGCEYGRYXFSAXFxcYGB0XGRcfGhkaHyggHh0lIBgYITEnMTUtLi4uFyAzODMsNygvLjcBCgoKDg0OFQ8PFS0fFSUyLS8rMi0rKy0xKy0tLS0yLS0tKy0zKy0rLSstKy03LS83Ny0tKy0tLTctNysrLisrN//AABEIAOEA4QMBIgACEQEDEQH/xAAbAAEAAwEBAQEAAAAAAAAAAAAABAUGAwcCAf/EAEUQAAIBAwMBBQQECQoHAQAAAAECAAMEEQUSITEGEyJBUTJhcYEUI5GhFTNCUnKSsdHhJENTYoKTorLB8DVUc4OUwvEH/8QAGAEBAQEBAQAAAAAAAAAAAAAAAAEDAgT/xAAbEQEBAAMBAQEAAAAAAAAAAAAAAQMRIQIxEv/aAAwDAQACEQMRAD8A9xiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgJW3Ou29tdrTq1BvboOswXbHtjc19Sa00JCCPCzFCcbsbTkdevTGOeehkfReya6fqlJtcqNUuqgZweCKSoMlmyeFyVX4t6QPVO+Xud5YbcZ3Z4x65mcvO2VNquzQ0N6+0k90RtXGcZY8dRjiZrXb+nfWlJrVjcWj1CjpSfG2oMqCXyBsLtTGCQCGVs8YM1beoafdhhRQKF7m1Uqc+IENV6keLjaFPHWF0l0+0F2mKdZrU1cb2A3uVViRT+rpAk52tzn8nznQaleP8AzoHw0+v/AO5EkaR2bFugXaKNMc7FJ3MfPc+ck8nJyScmXa6XRUfiwfjz+2EZ38K3VM81N3xsK4H2rnEkUterowFQW9QnyWqabn+xUGZdfguj/Rr9k+H0mmVwhdc+jnH2EkQIya+iMBfJUoH+uvHyIlpRrrcJmgwYeoOZWfgp7dcWzBl/MYYB/V8P2gyIdO2Vc0N1vU93sn4YOD8sfomBoolNb6o9vUCakvXgOPP9/wAOD7vOW9NxUQGmQQehHSB9REQEREBERAREQEREBERAREQEREBKHtP2gXSqPd2z0zdPju6bk+Ikgc7QSBz1n52n1OtaulLShTLurMzPVCd0igDfjHIyQM9BMdSqlkK6JVqlaifWX1UrVZiQ3FDnGfZywBXA4HAIC407Xhatci6oj6XSZsUdwDOpO9QrHgqFOc+ivwMETP3HZkXhe57WXDB3blUfgc5SmOMtjoAoBz6y8tuzFRnStZqFqE5d3PirKwBY1MdSdqnOM5HoTO5065udS3GioKptBY+ZIyQQCAOucZLccDbC1X1Fprowt7ei1NMBUBGSQPNh5Hz5OTn1yJvrW2W3T6lQmeTgecg6Xoi2T76zGrU9Two/QToPjyffLWEIiICIiAn4yh1wwyPQz9iBDuLQNTII3qeqnk/LPX4fYRKxGbS3yp3Uj5k9P0j927y/K/Ol/I9zRyCUGfUfnfx/b0MDpQrCvTynzHmD5gj1nSUdEmwrjuuUbhfePzPiOdvwKn8mXVNxUQFDkHkGB9REQEREBERAREQEREBERASJquoLpdg9Wvkheij2mY8Kq+8kgD4yXMh2jvDc6gFoHHdtspnGQK7KWeofIijS3EeRZtvXECrt7apqeoMK5V3dvrvNXdelLn+YoAnP51TP5pB1lhoa0Km65w5HAXbhFHlgev7J+9ntMWxtAVGCQAAeSqD2QSeST1J6knmW0BERAREQEREBERAREQERECDe24IIb2X6+WG8iD5ZOOfI4M46bXNOqUrdScHy8fXIHkHHi9xDCWToHQh+QeDKa7QrUBzzkIx9+QaTfrFf7xoF3E5W1Xv6CsOMjp6HzHyPE6wEREBERAREQEREBERAj6hc/Q7J6h52qTj1PkPmcD5zKaLZ/SdVIq+IUs0yfzmytW6b+1VZEP8A0DL3tDXWlRTvvYBNR/0KINQ/eFkXsXbNR0hWuABUYAvjp3j5q1ftqVH+yBoIiICIiAiIgIiICIiAiIgIiICV+pURUODwHUrn0Izg/HBY/ISwkXUR/Js+hB+/B+4mBw0et3iNu4Jw+PQt7Q+TBpYyh0mtt1V0b1bA9zClV/bUYS+gIiICIiAiIgIiICIiBmu2AFa1qo/RqQpf+Q60T/ml1pQxYL78t+sSf9ZT9pxnGf6Wh/hrU2H3iXWmf8OpfoL+wQJMREBERAREQEREBERAREQEREBIOtVjS09u7XczeEAnaMkHknyA6/KTpV9owGsAHG4bhlSQAeDwSeMZxAz1C/K613tNC6qFBI4Ullx4cnJIwPL7JtZ53SZX1Op9JBVxsQBTtpKCMgKrFSzkeezp0JE9EgIiICIiAiIgIiICIiBnu1akUAy+VWgx+Hf0t3+EMZbaSc6bTz5Lt/V8P+k465afTLCoi8FkKg+jEYU/InPyn5odx31BscZO8D0WoA4+8sPlAsoiICIiAiIgIiICIiAiIgIiRb6t3QA6ZyT8Bj98DtVrrSH1jAfOV2v3IOiOaJDbsKCDkEswHl8Z5x2h1Fr+/wB9u5VEOFCsVyR1JII/hiVtPtVWtKw77NRQQSxHiyMYJ4wSDzk/LM3uD1PO3EyS3T0LstT77V7qqDkNXcL8KCUbX/PSrTWTCdj+0tutLZRwB6A+IbmdySCcklnYnBPWblHDoCnIPImNmuV2+oiJAiIgIiICIiAiIgfFYZpnP+/X7pTWTfRNSKtwCdvycsyH5N3qD5S8lNq1DA3cjbw2Ouw4OR712q3/AGyPOBcxI2n3P0m38eNy+FgOm4enuPBHuIkmAiIgIiICIiAiIgIiICV2vWzXOmVBbfjNp2/Ej/59ksYgeK3VT6Nbd1dqVceTDn5H0/jMpWLF2CHIznAOTjpxzweP2es9n7aVrcuKd0ivhTVrEgnZSAxkBeTUc4VQOSekyVf/APPmu6AfT/AwA3U3w2xiMld467c48/jPXM8v3lZfjTDLXah7OSwPGM4yeOMehnv3ZreulU1uTlgg+3ofvEwPZvsLcLqCvqowiH2cjJIxj5T1CjT7tZlm9S3jTzOOkRExUiIgIiICIiAiIgJyr09wyPL7x+/zHwnWIGeDHSrsFBlCMADzQZO0e9eSvquV/JzL+lUFWmGpHIIyCPMSLe2orUiHGVP3HrkEcjnnI6EZlNQuX0a421gWpsfLzJ8x5BvVejdV8xCtLE50K63FENQIZT0InSEIiICInC6u0tAO+PLHAAGSfgIHeJxtbpLunuoHIBwfUEdQR5GdoCIiAJx1lbrmrDS7cbR3lWodtKkPaqP6e4DqT0ABMh9oLlDd0qPekO2T3SglmVQSx49ke8+oHGZn6tquu3z1bM1HpP8AVFmzudQF+qpHjZSJBLt7TYxnAgcdItW1a9Dswqjf3m/8mvXXjvAP+Xo9KfkzePyVjv7agLagFp9B9pPmT7yeZw02xFlS8txABwMAAdFUeSiTICIiAiIgIiICIiAiIgIiICIiAkS7tBVpEFdynqv7v3fZgyXEDC1aLWt6Ws7hxTWoKb0wcMahCMBk45CuG58gOTzLfTu0ZVEGrqKbtgbejqePbX5jkcSfd6OHq77fg7xUIPss4xg/HwgfCZqlo1yKZF7l2HJqGnvWozY3eFWDqo2gLtbwj16QrcxMTb1bmx4tCxA/JBFdB/ZIWqvwCt8ZJp9r3pMFu6SE/wBWqKbf3dba0Iub29dVqG2wFp8cjOTjJ8+nOPlMkO1D1rw1alIMy/VqocKPyizDd5nwjHopPlO5enqGoVDeNXtRUI2bjtXdjxDAJVsnnz9/Sc10k6delXON2DTdSSCBnOMHII3bsAk9ccDEDO6jr9zpNyqWD901zVO/IBKrTUFsbhwSWAz6fKbrs1dOKNHvaprGqW3ZfdtwpYfDpjHvkCjp1O/1QVL5RtojaSQfEzHcBt9Rn48kSNqd33+p50lmpd2u3NNUZ2JOCBvcIGGfPO3ByMkQNrf39LTrcvfVFpqPNjiZbVO1T3ChdMU0Uf2aroTUqevcUB4n8vEcIAck8SDa6I9e53nLVAfxhb6TXB8iKlQCjRPXIVT14aaLTuzq0SWq8FvaO4tUfHTfVbxH4eXlAzmmaG95UbvVOG5qKzb3qHqDc1Rw3upL4Bk+31F7V0htOqGpTc92BvbnBDDk7V6c/wCpmhpUhRphaQCgeQnzdFVtmNwQqAZJJxgeuYHzbXiXI+pbJxnHQ4PQ49JWa/2gTSaXA3MTt927yHAJJ9wBP2TG6jqFWjqIpWZZVqjep3ruV2BJZ+jeIZA6L7XPSS00RNRrMbSslSoG3Akg1ab4AP1TjwnAGM59ceofdTt81u4eqEej7JKDhHAzh6hfwEgHAZB1HM2Wk6nT1ayWraHKsAffyMjiYDVbeva02S2ttgYbWbIpEL5bG27eDgjcxxtxjxGTuxlvX0vVDTvu7ZX9lqWSrALgktjqSAcEk+I4yBA3sREBERAREQEREBERAREQEREBERA5VrZK/wCOUN8RyPgZEuNMU0j3RbpwpbcCfTx5lhEDzayshed4blgtRTwtNduGONwK8MPNcYzgc4lnYJVRjSqOtVQeVCYVcDwtksdpz5dcHoekte0dn9O1S2QHZncS44bA25AI58/szPm9uKVnavSsPyVOdq7vHtJALeyDgE4JHJEDP3ts1ekMuwpN7C0iAmTksCzKS2fTHQdDjj9rC2o0qVClsNRqnhG47sMSFG45KLjI58+ksNO3ac7UthuFY5VBtYkPuZ1O5scFWbk+ZHpOr2yXd7T/AAatUHJVxUpMoRDyTudQc5AwMnOeOORRpNNtzaWFOnUbcVUAt6kDmSYiQJgNc1Q6r2jWiRutlcU/ZGN+0VS7sei4G0Y5yffN/PMl0/8AlbU6SGjUfeHL4JqMqtTpMGH82NpyvB3Op9YGZs3qntHUubimUS4Pdh0Qpb11pHcz/WMWDk06eOg275q3qfhmj3FHuwyjaK7JvdCQR4eABtBDgk4PAwQ2ZUWlyur6nptKhVzsoik9LDkI6Iylxk93knb05x18poV7LXNvehbNaaUgB4g3nk58AA49P4QNPoFu9iHpVKhqouChPVQRyvUnA4I/Sx5SyFugqbgq59cDP2znYWv0WgAx3HzP+/8AfMkwEREBERAREQEREBERAREQEREBERAREQI97Y0r+kFvqaVVByFdQ4BHQ4YdeT9s/K9ilSwNGmBTUjA2gAL6YHTg8yTECo0nSGtbjvLxw742jaGA958bMc+gzgZPXJMt4iAiIgJntb7K0tQ1EXNuBTuAAu/nxKrKwBAIz7PnnGTNDEDG9m+x34K1jvXICICKagAHlnbxkHDFd5VTgYUAc5M2URAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQP//Z"
            />
          </div>
          <p class="mt-2 text-lg text-center font-bold">Charm cho nữ</p>
        </div>
        <div class="flex flex-col items-center">
          <div class="h-25 w-25">
            <img class="h-full w-full" src="./assets/images/charmNam.jpg" />
          </div>
          <p class="mt-2 text-lg text-center font-bold">Charm cho nam</p>
        </div>
        <div class="flex flex-col items-center">
          <div class="h-25 w-25">
            <img
              class="h-full w-full"
              src="data:image/jpeg;base64,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tYkPuZ1O5scFWbk+ZHpOr2yXd7T/AAatUHJVxUpMoRDyTudQc5AwMnOeOORRpNNtzaWFOnUbcVUAt6kDmSYiQJgNc1Q6r2jWiRutlcU/ZGN+0VS7sei4G0Y5yffN/PMl0/8AlbU6SGjUfeHL4JqMqtTpMGH82NpyvB3Op9YGZs3qntHUubimUS4Pdh0Qpb11pHcz/WMWDk06eOg275q3qfhmj3FHuwyjaK7JvdCQR4eABtBDgk4PAwQ2ZUWlyur6nptKhVzsoik9LDkI6Iylxk93knb05x18poV7LXNvehbNaaUgB4g3nk58AA49P4QNPoFu9iHpVKhqouChPVQRyvUnA4I/Sx5SyFugqbgq59cDP2znYWv0WgAx3HzP+/8AfMkwEREBERAREQEREBERAREQEREBERAREQI97Y0r+kFvqaVVByFdQ4BHQ4YdeT9s/K9ilSwNGmBTUjA2gAL6YHTg8yTECo0nSGtbjvLxw742jaGA958bMc+gzgZPXJMt4iAiIgJntb7K0tQ1EXNuBTuAAu/nxKrKwBAIz7PnnGTNDEDG9m+x34K1jvXICICKagAHlnbxkHDFd5VTgYUAc5M2URAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQP//Z"
            />
          </div>
          <p class="mt-2 text-lg text-center font-bold">Charm cho nữ</p>
        </div>
      </div>
    </div>

    <div class="mx-auto my-10 w-3/5 border-2"></div>

    <div class="mt-20 h-96 w-4/5 mx-auto grid grid-cols-2 gap-10">
      <div class="h-full w-full bg-slate-500 border">
        <img class="w-full h-full object-fill"/>
      </div>
      <div class="h-full w-full grid grid-rows-2 gap-8">
        <div class="h-full w-full grid grid-cols-2 gap-10">
          <div class="h-full w-full bg-slate-500 border"></div>
          <div class="h-full w-full bg-slate-500 border"></div>
        </div>
        <div class="h-full w-full bg-slate-500 border"></div>
      </div>
    </div>

    <div class="mx-auto my-10 w-3/5 border-2"></div>

    <div class="w-full py-16 bg-slate-200 h-vh">
      <div class="w-4/5 mx-auto grid gap-12 grid-cols-10">
        <div class="col-span-4 h-80 bg-red-300 w-full"></div>
        <div class="col-span-6 flex flex-col justify-between">
          <div>
            <p class="font-bold text-4xl">THE LOVE OF MINE</p>
            <p class="text-xl mt-5 max-w-[650px]">Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio quo soluta facere illo rerum debitis sint ex recusandae, culpa quos? Atque, quos. Praesentium, asperiores corporis. Facilis non consequuntur excepturi eos!</p>  
          </div>
          <div>
            <button class="text-2xl font-bold text-white bg-red-500 px-5 py-3 rounded">READ NOW</button>
          </div>
        </div>
      </div>
      <div class="w-4/5 mx-auto mt-16">
        <h1 class="font-bold text-5xl text-center">KHÁM PHÁ USBIBRACELET</h1>
        <div class="mt-10 grid grid-cols-3 gap-10">
          <div>
            <div class="h-60 w-full rounded bg-slate-500">
            </div>
            <div class="mt-6 text-center">
              <h1 class="text-2xl font-bold">DISNEY X USBI</h1>
              <a class="mt-2 underline italic">Khám phá ngay</a>
            </div>
          </div>
          <div>
            <div class="h-60 w-full rounded bg-slate-500">
            </div>
            <div class="mt-6 text-center">
              <h1 class="text-2xl font-bold">DISNEY X USBI</h1>
              <a class="mt-2 underline italic">Khám phá ngay</a>
            </div>
          </div>
          <div>
            <div class="h-60 w-full rounded bg-slate-500">
            </div>
            <div class="mt-6 text-center">
              <h1 class="text-2xl font-bold">DISNEY X USBI</h1>
              <a class="mt-2 underline italic">Khám phá ngay</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mx-auto grid grid-cols-3 gap-24 my-10 w-4/5">
      <div class="flex flex-col items-center">
        <div class="h-24 w-1/2">
          <img class="h-full w-full" src="https://lamha.com.vn/image/cache/catalog/blog/khuyen-mai/free_shipping_PNG2-640x360.png"/>
        </div>
        <div class="text-center mt-5">
          <p class="font-bold text-2xl">Miễn phí vận chuyển</p>
          <p class="mt-2">Miễn phí giao hàng trên toàn quốc với mọi giá trị đơn hàng</p>
        </div>
      </div>
      <div class="flex flex-col items-center">
        <div class="h-24 w-1/2">
          <img class="h-full w-full" src="https://lamha.com.vn/image/cache/catalog/blog/khuyen-mai/free_shipping_PNG2-640x360.png"/>
        </div>
        <div class="text-center mt-5">
          <p class="font-bold text-2xl">Miễn phí vận chuyển</p>
          <p class="mt-2">Miễn phí giao hàng trên toàn quốc với mọi giá trị đơn hàng</p>
        </div>
      </div>
      <div class="flex flex-col items-center">
        <div class="h-24 w-1/2">
          <img class="h-full w-full" src="https://lamha.com.vn/image/cache/catalog/blog/khuyen-mai/free_shipping_PNG2-640x360.png"/>
        </div>
        <div class="text-center mt-5">
          <p class="font-bold text-2xl">Miễn phí vận chuyển</p>
          <p class="mt-2">Miễn phí giao hàng trên toàn quốc với mọi giá trị đơn hàng</p>
        </div>
      </div>
    </div>

    <!-- footer -->
    <div
      class="mt-20 min-h-40 grid-cols-4 bg-[#FDF8F8] px-16 pt-6 text-[#CE112D]"
    >
      <div class="mb-3">
        <input
          class="py-2 px-5 border rounded"
          placeholder="Nhập email của bạn ..."
        />
        <button class="bg-[#FFEAEA] w-32 font-bold h-10 rounded">
          Đăng ký
        </button>
      </div>
      <div class="grid grid-cols-4 gap-10">
        <div>
          <h1 class="text-3xl font-bold">Usbibracelet</h1>
          <h3 class="mt-2 text-lg">Đăng ký</h3>
          <h1 class="mt-2 text-xl font-semibold italic">
            Nhận ngay mã giảm giá 12%
          </h1>
        </div>
        <div>
          <h1 class="text-lg">Hỗ trợ</h1>
          <h3 class="mt-2 text-sm">Đường CMT8, Quận 10, TP HCM</h3>
          <h3 class="mt-2 text-sm">Usbi@gmail.com</h3>
          <h3 class="mt-2 text-sm">08358588484</h3>
        </div>
        <div>
          <h1 class="text-lg">Menu</h1>
          <a href="/index.html" class="mt-2 block text-sm">Trang chủ</a>
          <a href="/blog.html" class="mt-2 block text-sm">Bài viết</a>
          <a class="mt-2 block text-sm">Cửa hàng</a>
          <a class="mt-2 block text-sm">Câu chuyện Usbi</a>
          <a class="mt-2 block text-sm">Giỏ hàng</a>
        </div>
        <div>
          <h1 class="text-lg">Theo dõi Usbi tại</h1>
        </div>
      </div>
    </div>
    <!-- end footer -->
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script>
        var splide = new Splide( '.splide' );
        splide.mount();
        btn_login = document.getElementById("btn_login");
        btn_login.addEventListener("click", function () {
        window.location.href = "./login.php";
        });
    </script>
  </body>
</html>
