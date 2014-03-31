<?php
/**
 * @package ImpressPages
 *
 */
namespace Plugin\AudioWidget\Widget\Audio;


class Controller extends \Ip\WidgetController
{
    public function getTitle()
    {
        return __('Audio', 'ipAdmin', false);
    }

    /**
     * Update widget data
     *
     * This method is executed each time the widget data is updated.
     *
     * @param $widgetId Widget ID
     * @param $postData
     * @param $currentData
     * @return array Data to be stored to the database
     */
    public function update($widgetId, $postData, $currentData)
    {
        return $postData;
    }

    public function generateHtml($revisionId, $widgetId, $data, $skin)
    {
        $audioHtml = $this->generateAudioHtml($data);
        if ($audioHtml) {
            $data['audioHtml'] = $audioHtml;
        }
        return parent::generateHtml($revisionId, $widgetId, $data, $skin);
    }

    protected function generateAudioHtml($data)
    {

        $output = $this->renderFilePlayersHtml($data);

        return $output;
    }

    protected function renderFilePlayersHtml($data)
    {

        $output = false;

        if (isset($data['source']) && $data['source'] == 'file') {
            if (isset($data['audioFiles'])) {
                foreach ($data['audioFiles'] as $fileData) {

                    if (isset($fileData['fileUrl']) && isset($fileData['fileName'])){
                        $output .= ipView('view/file.php', array('fileUrl' => $fileData['fileUrl'], 'fileName' => $fileData['fileName']))->render();
                    }
                }
            }
        } else {
            if (isset($data['soundcloudUrl'])) {
                $output .= $this->renderSoundcloudUrl($data);
            }
        }
        return $output;

    }

    protected function renderSoundcloudUrl($data)
    {

        $output = null;
       if (isset($data['soundcloudUrl'])) {

            $url = $data['soundcloudUrl'];

//               $url = 'https://www.soundcloud.com/humanoide/sets/humanoide-en-concert-avec-la/'; //TODO X

            if (!preg_match('/^((http|https):\/\/)/i', $url)) {
                $url = 'https://' . $url;

            }

            if (preg_match('/^((http|https):\/\/)?(www.)?(player.)?soundcloud.com/i', $url)) {

                //                $url = str_replace('www.soundcloud.com', 'soundcloud.com', $url);

                //                $track = 5370961;
                //                $data['track'] = $track;

                //                return $this->renderView('view/soundcloud.php',  $url, $data);


                //            data['soundcloudUrl'];
                $soundcloudIframeUrl = str_replace('https://', 'https%3A//', $url);
                return ipView('view/soundcloud.php', array('soundcloudIframeUrl' => $url));
            }
        }
        return false;

    }



    public function adminHtmlSnippet()
    {


        $form = $this->editForm();

        $variables = array(
            'form' => $form
        );
        return ipView('snippet/edit.php', $variables)->render();
    }

    protected function editForm()
    {
        $form = new \Ip\Form();
        $form->addClass('ipsAudioForm');

        // Add values and indexes
        $values = array(
            array('file', __('File', 'ipAdmin', false)),
            array('soundcloud', __('Soundcloud', 'ipAdmin', false)),
        );

// Add a field
        $form->addField(new \Ip\Form\Field\Select(
            array(
                'name' => __('source', 'ipAdmin', false), // set HTML 'name' attribute
                'label' => 'Audio source:',
                'values' => $values
            )));

        $fieldset = new \Ip\Form\Fieldset('Soundcloud');
        $fieldset->addAttribute('id', 'ipsAudioSoundcloud');
        $form->addFieldset($fieldset);

        $field = new \Ip\Form\Field\Text(
            array(
                'name' => 'soundcloudUrl',
                'label' => __('Soundcloud URL', 'ipAdmin', false),
            ));
        $form->addField($field);

        $fieldset = new \Ip\Form\Fieldset('File');
        $fieldset->addAttribute('id', 'ipsAudioFile');
        $form->addFieldset($fieldset);

        return $form; // Output a string with generated HTML form
    }

}
