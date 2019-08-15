<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\models\CommentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerAssetBundle('app\assets\AppAsset');


$this->title = 'Comments';
$this->params['breadcrumbs'][] = $this->title;

?><style>
ul {
    margin: 0;
    padding: 5px 0;
    list-style: none;
}

li {
    margin: 0;
    padding: 5px;
}
span {
    float: right;
    color: #999999;
}
div.pagination{
    padding-top: 5px;
}
div.comments{
    padding-bottom: 10px;
}
</style>
<div class="user-comments-index">



    <h1><?= Html::encode($this->title) ?></h1>

    <a href="/comments/create" class="btn btn-success">Create a comment</a>
  <div class="comments">  
    <ul>
    <?php foreach($models as $model){
    ?>    
        <li>
           <?php
                echo   $model->logged_user;
                echo    '<br>';
                echo  '<span>'.$model->created_at.'</span>';
                echo   $model->comment;
        

            

              }  ?>
   </div>           
            <div class="pagination">  
              <?php
              echo LinkPager::widget([
                'pagination' => $pagination,
            ]);
            ?>
           </div> 
        </li>   
    </ul>
</div> 
