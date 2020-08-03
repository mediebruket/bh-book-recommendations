<?php

final class BookRecommendationsReview
{
    public $title;
    public $link;
    public $description;
    public $pubDate;
    public $author;
    public $imageURL;

    public function __construct($item)
    {
        $this->title = $item->get_title();
        $this->link = esc_url($item->get_link());
        $this->description = htmlspecialchars_decode(wp_trim_words(wp_strip_all_tags($item->get_description())));
        $this->pubDate = $item->get_date(get_option('date_format'));
        $this->setAuthor($item);
        $this->setImageUrl($item);
    }

    public function __toString()
    {
        return $this->title;
    }
    private function setAuthor($item)
    {
        if ($author = $item->get_author()) {
            $this->author = $author->get_name();
        }
    }

    private function setImageUrl($item)
    {
        if ($enclosure = $item->get_enclosure()) {
            $image_type = explode('/', $enclosure->get_type());

            if ($image_type && $image_type[0] == 'image') {
                $this->imageURL = $enclosure->get_link();
            }
        }
    }
}
