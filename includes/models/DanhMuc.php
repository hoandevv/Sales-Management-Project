<?php
class DanhMuc extends BaseModel {
    protected $table = 'tbl_danhmuc';
    protected $primaryKey = 'id_danhmuc';
    
    public function getTree() {
        $categories = $this->findAll(
            'is_active = 1',
            [],
            'parent_id ASC, thutu ASC'
        );
        
        return $this->buildTree($categories);
    }
    
    private function buildTree(array $elements, $parentId = null) {
        $branch = [];
        
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id_danhmuc']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        
        return $branch;
    }
    
    public function getParents() {
        return $this->findAll(
            'parent_id IS NULL AND is_active = 1',
            [],
            'thutu ASC'
        );
    }
    
    public function getChildren($parentId) {
        return $this->findAll(
            'parent_id = ? AND is_active = 1',
            [$parentId],
            'thutu ASC'
        );
    }
    
    public function getBreadcrumb($categoryId) {
        $breadcrumb = [];
        $category = $this->find($categoryId);
        
        while ($category) {
            array_unshift($breadcrumb, $category);
            if ($category['parent_id']) {
                $category = $this->find($category['parent_id']);
            } else {
                break;
            }
        }
        
        return $breadcrumb;
    }
    
    public function create($data) {
        if (!isset($data['slug'])) {
            $data['slug'] = Utility::generateSlug($data['tendanhmuc']);
        }
        return parent::create($data);
    }
    
    public function update($id, $data) {
        if (isset($data['tendanhmuc']) && !isset($data['slug'])) {
            $data['slug'] = Utility::generateSlug($data['tendanhmuc']);
        }
        return parent::update($id, $data);
    }
}