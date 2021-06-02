<?php
namespace RedMonks\HyvaSalesGrids\Model\MassAction;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;

class Filter extends \Magento\Ui\Component\MassAction\Filter
{
    /*
     * This avoids call of function  $this->getFilterIds(); as that look for
     * UI component data provider stuff
     * */
    public function getCollection(AbstractDb $collection)
    {
        $selected = $this->request->getParam(static::SELECTED_PARAM);
        $excluded = $this->request->getParam(static::EXCLUDED_PARAM);

        $isExcludedIdsValid = (is_array($excluded) && !empty($excluded));
        $isSelectedIdsValid = (is_array($selected) && !empty($selected));

        if ('false' !== $excluded) {
            if (!$isExcludedIdsValid && !$isSelectedIdsValid) {
                throw new LocalizedException(__('An item needs to be selected. Select and try again.'));
            }
        }

        $filterIds = [];
        if (\is_array($selected)) {
            $filterIds = array_unique(array_merge($filterIds, $selected));
        }
        $collection->addFieldToFilter(
            $collection->getResource()->getIdFieldName(),
            ['in' => $filterIds]
        );

        return $collection;
    }

    public function getComponentRefererUrl()
    {
        return "sales/*/";
    }
}
