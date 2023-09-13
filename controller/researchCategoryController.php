<?php
include_once 'repository/researchCategoryRepository.php';

$idCategory = 0;

if (isset($_GET['idCategory'])) {
    $idCategory = $_GET['idCategory'];
}

$result = getResearchByCategory($idCategory);
$category = getCategory($idCategory);

function renderResearchByCategory()
{
    global $result;
    //tính tổng số bản ghi, số trang, số size khi phân trang
    $Index = $_GET["Index"] ?? 1;
    $size = $_GET["size"] ?? 3;
    $skip = ($Index - 1) * $size;
    $total = count($result);
    $count = ceil($total / $size);
    $result = array_slice($result, $skip, $size);

    $html = '';

    foreach ($result as $r) {
        $html = $html . '
        <div class="col-md-4  mt-5">
            <div class="similar-img">
                <img class="full-w-h" src="' . $r['img'] . '">
            </div>
            <div class="name-sci text-center">
                <h5 class="bold"><a href="' . $r['slug'] . $r['id'] . '">' . $r['name'] . '</a></h5>
                <h6>' . $r['prizeName'] . ' ' . $r['award_year'] . '</h6>
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
