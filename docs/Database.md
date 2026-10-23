
#module User

User
1. uuid
2. username
3. roles array[]
4. token

Roles
1. uuid
2. name
3. alias

#module PIM

Unit
1. uuid
2. name
3. code

Brand
1. uuid
2. name
3. code

ProductLine
1. uuid
2. name
3. code

Product 
1. uuid
2. user_id
3. unit_id
4. brand_id
5. product_line_id
6. article (sku)
7. barcode (sku)
7. is_deleted
8. date_update
9. date_create

---
Wildberries module
---

1. #### Product
   - uuid 
   - 
   - user_id
   - is_deleted 
   - created_at
   - updated_at

2. #### Attributes

   - uuid
   - name
   - description
   - type
   - alias
   - attribute_group_id
   - is_required
   - is_popular
   - is_dictionary
   - max_count
   - is_read_only
   - is_visible
   - is_deleted
   - created_at
   - updated_at

3. ### Attribute Group
   
   - uuid
   - name
   - alias
   - type
   - order
   - is_deleted
   - created_at
   - updated_at

4. ### Catalog
   - Uuid $catalogId
   - int $objectId
   - int $parentId
   - string $name
   - ?int $level = null
   - bool $isVisible = true
   - bool $isActive = true
   - bool $isDeleted = false
   - DateTime $createdAt
   - DateTime $updatedAt

5. ### Product Attribute
   - uuid

7. ### Product Attribute History
   - uuid 

8. ### Suggest
   - uuid