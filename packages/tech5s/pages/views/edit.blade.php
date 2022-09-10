<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{'grapes/libs/bootstrap/bootstrap.min.css'}" rel="stylesheet">
    <link rel="stylesheet" href="{'grapes/libs/grapes/grapes.min.css'}">
    <link rel="stylesheet" href="{'grapes/scss/style.css'}">
    <link rel="stylesheet" href="{'grapes/libs/fontawesome/css/all.min.css'}">
    <script src="{'grapes/libs/bootstrap/bootstrap.bundle.min.js'}" defer></script>
    <script src="{'grapes/libs/grapes/grapes.min.js'}" defer></script>
    <script src="{'grapes/plugins/grapesjs-blocks-basic.min.js'}" defer></script>
</head>

<body data-id="{-currentItem.id-}">
    <div id="navbar" class="sidenav d-flex flex-column overflow-scroll">
        <nav class="navbar navbar-custom navbar-light">
            <div class="container-fluid">
                <span class="navbar-brand h3 logo mb-0 text-white">EDIT PAGE</span>
            </div>
        </nav>
        <div class="d-flex flex-column my-2 mx-2">
            <ul class="list-group pages mt-3">
                <li class="list-group-item d-flex justify-content-between">{-currentItem.name-}
                    <div>
                        <a class="text-success" href="/{-currentItem.slug-}" target="_blank"><i class="fa-solid fa-up-right-from-square"></i></a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="action-block">
            <ul class="nav nav-tabs" roles="tablist">
                <li class="nav-items" role="presentation">
                    <button class="nav-link active" id="block-tab" data-bs-toggle="tab" data-bs-target="#block" aria-selected="true" aria-controls="block">
                        <i class="fa-solid fa-cubes"></i>
                    </button>
                </li>
                <li class="nav-items" role="presentation">
                    <button class="nav-link" id="layer-tab" data-bs-toggle="tab" data-bs-target="#layer" aria-selected="true" aria-controls="layer">
                        <i class="fa-solid fa-layer-group"></i>
                    </button>
                </li>
                <li class="nav-items" role="presentation">
                    <button class="nav-link" id="style-tab" data-bs-toggle="tab" data-bs-target="#style" aria-selected="true" aria-controls="style">
                        <i class="fa-solid fa-palette"></i>
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="block" role="tab" aria-labelledby="block-tab">
                    <div id="blocks"></div>
                </div>
                <div class="tab-pane fade" id="layer" role="tab" aria-labelledby="trait-tab">
                    <div id="layers"></div>
                </div>
                <div class="tab-pane fade" id="style" role="tab" aria-labelledby="style-tab">
                    <div class="panel_classes"></div>
                    <div id="trait"></div>
                    <div id="styles"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-content">
        <nav class="navbar navbar-custom navbar-light">
            <div class="container-fluid">
                <div class="panel__show_styles"></div>
                <div class="panel__devices"></div>
                <div class="panel__basic-actions"></div>
            </div>
        </nav>
        <div id="editor">
        </div>
    </div>
    <script src="{'grapes/app.js'}" type="module" defer></script>
</body>

</html>
