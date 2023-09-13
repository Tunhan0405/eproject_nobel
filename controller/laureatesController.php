<?php
require 'repository/laureatesRepository.php';
$idCategory = 0;
if (isset($_GET['idCategory'])) {
    $idCategory = $_GET['idCategory'];
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $keyword = "";
    if (isset($_GET["keyword"])) {
        $keyword = $_GET["keyword"];
    }
}

$result = getLaureatesByCategoryId($idCategory);
$cate = getPrizeCategoryById($idCategory);

function renderLaureatesByCategory()
{
    global $result;

    //tính tổng số bản ghi, số trang, số size khi phân trang
    $Index = $_GET["Index"] ?? 1;
    $size = $_GET["size"] ?? 3;
    $skip = ($Index - 1) * $size;
    $total = count($result)+1;
    $count = ceil($total / $size);
    $result = array_slice($result, $skip, $size);

    $html = '';
    foreach ($result as $r) {
        $html = $html . '
            <div class="col-sm-6 mb-3 mb-sm-0 mt-5 lg-12 mb-5">
                <div class="card">
                    <div class="card-body bl-common">
                        <div class="row">
                            <div class=" card-img col-md-4 lg-4">
                                <div class="bl-img " >
                                <img src="' . $r['avatar'] . '" style="width:200px;height:300px">
                                </div>
                            </div>
                            <div class="card-body col-md-8 lg-8">
                                <div class="text-content">
                                    <div class="name">
                                        <div>
                                            <h5><b><a href="' . $r['slug'] . $r['id'] . '">' . $r['name'] . '</a></b></h5> 
                                        </div>
                                        
                                    </div>
                                    <div class="year">
                                
                                        <div>
                                            <b>' . $r['categoryname'] . ' in ' . $r['award_year'] . '</b>
                                        </div>
                                    </div>
                                    <div class="ly-do">
                                        <div>
                                            ' . $r['reason_winning'] . '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>   
        ';
    }
    echo $html;

    //kiểm tra url có chưa tham số hay không
    $current_url = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $current_url .= "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    //xóa index và size khi load 
    $current_url = preg_replace('/&Index=[0-9]+/', '', $current_url);
    $current_url = preg_replace('/&size=[^&]*/', '', $current_url);

    $parsed_url = parse_url($current_url);
    if (isset($parsed_url['query'])) {
        $current_url .= "&Index=";
    } else {
        $current_url .= "?Index=";
    }

    $pagination = '';
    if ($count > 1) {
        $pagination = $pagination . '<ul id="pagination">';
        if ($Index > 1) {
            $pagination = $pagination . '<li class="page-item"><b><a class="page-link" href="' . $current_url . ($Index - 1) . '&size=' . $size . '">Previous</a></b></li>';
        }
        for ($i = 1; $i <= $count; $i++) {
            $active_class = ($i == $Index) ? 'active' : '';
            $pagination = $pagination . '<li class="page-item"><b><a class="page-link ' . $active_class . '" href="' . $current_url . $i . '&size=' . $size . '">' . $i . '</a></b></li>';
        }
        if ($Index < $count) {
            $pagination = $pagination . '<li class="page-item"><b><a class="page-link" href="' . $current_url . ($Index + 1) . '&size=' . $size . '">Next</a></b></li>';
        }
        $pagination = $pagination . '</ul>';
    }
    echo $pagination;
}
